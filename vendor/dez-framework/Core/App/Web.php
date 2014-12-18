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

        Dez\View\View,
        Dez\Web\Layout,
        Dez\Response\Response;

	class Web extends Core\App {

		public
			$environment = 'web';

		public function __construct( Core\Config $config ){
            parent::__construct();

            date_default_timezone_set( $config->path( 'base.time_zone' ) );
			$this->attach( 'config', $config );

            try {
                $this->init();
            } catch ( \Exception $e ) {
                ErrorMessage::fatal( Utils\HTML::tag( 'b', get_class( $e ) ) .': '. $e->getMessage() );
            }
		}

		public function run() {
            try{
                $content = $this->action->execute();
                if( $this->response->getFormat() == Response::RESPONSE_HTML ) {
                    $this->layout->setContent( $content )
                        ->set( 'errorMessages',     ErrorMessage::instance()->render() )
                        ->set( 'infoMessages',      SystemMessage::instance()->render() )
                        // @TODO Эту ебалу описывать надо явно не тут...
                        ->js( '@js/dom.js' )->js( '@js/jquery-2.1.1.min.js' )->css( '@css/main.css' );
                    $this->response->setBody( $this->layout->output() );
                } else {
                    $this->response->setBody( $content );
                }
                $this->response->send();
            }catch( \Exception $e ){
                ErrorMessage::fatal( $e->getMessage() );
            }
		}

		private function init(){
            $this->initSession();
            $this->initError();
            $this->initRequest();
            $this->initRouter();
            $this->initAction();
            $this->initDatabase();
            $this->initView();
            $this->initLayout();
            $this->initResponse();
            // @TODO Вспомнил что надо хэдеров дописать ? и решил блять их дописать сука здесь ?
			$this->response->setHeader( 'X-Content-By', \Dez::poweredBy() );
			$this->response->setHeader( 'X-Author', DEZ_AUTHOR );
		}

        protected function initSession() {
            $this->attach( 'session', Core\Session::instance() );
        }

		private function initDatabase() {
            Loader::addIncludeDirs( DEZ_PATH . DS . 'Db' );
			ORM::init( APP_PATH . DS . 'conf' . DS . 'app.ini', 'dev' );
            $this->attach( 'db', ORM::connect() );
            if( \Dez::cfg()->path( 'debug.enable' ) ) {
                ORM\Common\Event::instance()->attach( 'query', function( $query = null ){
                    Helper\Debug::instance()->sql( $query );
                } );
            }
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
            $xmlRoutes 		= APP_PATH . DS . 'conf' . DS . $config->path( 'base.router_config' );
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
            // @TODO Просто пиздец... Сделай сука нормально! Ебаный ты в рот. Ваня.
            $uri = Core\URI::getInstance( $url );
            $url = $uri->buildURL( 'scheme', 'host', 'path', 'query' );
            header( 'HTTP/1.1 302 Moved Temporarily' );
            header( 'Location: ' . $url );
            die;
        }

	}