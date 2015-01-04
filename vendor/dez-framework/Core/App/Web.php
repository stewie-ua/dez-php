<?php

	namespace Dez\Core\App;

    use Dez\Autoloader as Loader,
        Dez\Core,
        Dez\Core\Url,
        Dez\Core\Message as SystemMessage,
        Dez\ORM,
        Dez\Error,
        Dez\Error\Error as ErrorMessage,
        Dez\Utils,
        Dez\Helper,

        Dez\Auth\Auth,
        Dez\View\View,
        Dez\Web\Layout,
        Dez\Response\Response,
        Dez\Dispatcher\Dispatcher;

	class Web extends Core\App {

		public
			$environment = 'web';

		public function __construct( Core\Config $config ){
            parent::__construct();

            date_default_timezone_set( $config->path( 'main.time_zone' ) );
			$this->attach( 'config', $config );

            try {
                $this->preInit();
            } catch ( \Exception $e ) {
                ErrorMessage::fatal( Utils\HTML::tag( 'b', get_class( $e ) ) .': '. $e->getMessage() );
            }
		}

        public function preInit() {
            $this->initDispatcher();
            $this->dispatcher->dispatch( 'beforePreInit', [ $this ] );
            $this->initSession();
            $this->initError();
            $this->initRequest();
            $this->initRouter();
            $this->initAction();
            $this->initDatabase();
            $this->initView();
            $this->initLayout();
            $this->initResponse();
            return $this;
        }

        public function init(){
            $this->dispatcher->dispatch( 'beforeInit',  [ $this ] );
            $this->initAuth();
            $this->dispatcher->dispatch( 'afterInit',   [ $this ] );
            return $this;
        }

		public function run() {
            $this->dispatcher->dispatch( 'beforeRun', [ $this ] );
            try{
                $this->response->setHeader( 'Dez-Powered',          \Dez::poweredBy() );
                $this->response->addHeader( 'Dez-Powered',          DEZ_CODENAME );
                $this->response->setHeader( 'Dez-Framework-Author', DEZ_AUTHOR );
                $content = $this->action->execute();
                $this->dispatcher->dispatch( 'afterExecute', [ $this ] );
                if( $this->response->getFormat() == Response::RESPONSE_HTML ) {
                    $this->layout->setContent( $content )
                        ->set( 'errorMessages',     ErrorMessage::instance()->render() )
                        ->set( 'infoMessages',      SystemMessage::instance()->render() );
                    $this->dispatcher->dispatch( 'beforeRender', [ $this ] );
                    $this->response->setBody( $this->layout->output() );
                } else {
                    $this->response->setBody( $content );
                }
                $this->dispatcher->dispatch( 'beforeSend', [ $this ] );
                $this->response->send();
                $this->dispatcher->dispatch( 'afterSend', [ $this ] );
            }catch( \Exception $e ){
                ErrorMessage::fatal( $e->getMessage() );
            }
		}

        protected function initDispatcher() {
            $this->attach( 'dispatcher', Dispatcher::instance()->loadHooks() );
        }

        protected function initSession() {
            $this->attach( 'session', Core\Session::instance() );
        }

        protected function initDatabase() {
            Loader::addIncludeDirs( DEZ_PATH . DS . 'Db' );
			ORM::init( APP_PATH . DS . 'conf' . DS . 'app.ini', 'dev' );
            $this->attach( 'db', ORM::connect() );
            if( \Dez::cfg()->path( 'debug.enable' ) ) {
                ORM\Common\Event::instance()->attach( 'query', function( $query = null ){
                    Helper\Debug::instance()->sql( $query );
                } );
            }
		}

        protected function initAuth() {
            $this->attach( 'auth', new Auth() );
        }

		protected function initError() {
            // @TODO Срала, мазала, мисыла... вот что я думаю об этом методе
			register_shutdown_function( function(){

				$error      = error_get_last();
                $highlight  = null;

                if( \Dez::app()->config->path( 'debug.enable_highlight' ) != 0 ) {
                    $highlight = Utils\Debug::highlight( $error );
                }

				switch( true ) {
					case ( $error['type'] & ( E_ERROR | E_WARNING | E_COMPILE_ERROR ) ): {
                        ErrorMessage::critical( $error['message'] . ' ['. $error['file'] .':'. $error['line'] .']' . $highlight );
						break;
					}
					case ( $error['type'] & ( E_NOTICE ) ): {
                        ErrorMessage::notify( $error['message'] . ' ['. $error['file'] .':'. $error['line'] .']' . $highlight );
						break;
					}
					default : {
					    break;
					}
				}
			});

		}

        protected function initRequest() {
            $this->attach( 'request', Core\Request::instance() );
        }

        protected function initRouter() {
            // @TODO Хуйня, но ладно, ебись оно конем, работает - не трогай
            $config 	    = \Dez::cfg();
            $cacheDir 		= APP_PATH . DS . 'cache' . DS . 'system';
            $xmlRoutes 		= APP_PATH . DS . 'conf' . DS . $config->path( 'main.router_config' );
            $router         = Core\Router::instance(  $xmlRoutes, $cacheDir, $config->path( 'debug.enable' ) );
            $this->attach( 'router', $router );
        }

        protected function initAction() {
            $this->attach( 'action', Core\Action::instance( $this->router, $this->request ) );
        }

        protected function initLayout() {
            $this->attach( 'layout', 	Layout::instance()->setPath( \Dez::getAlias( '@app/view' ) ) );
        }

        protected function initResponse() {
            $this->attach( 'response', 	Response::instance() );
        }

        protected function initView() {
            $this->attach( 'view', View::instance()->setPath( \Dez::getAlias( '@app/view' ) ) );
        }

        public function redirect( $url = null ){
            $this->response->setCode( 302 )->setHeader( 'Location', Url::instance( $url )->getFull() )->send(); die;
        }

	}