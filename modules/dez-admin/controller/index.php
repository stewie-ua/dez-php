<?php

    use Dez\Controller\Controller,
        Dez\Response\Response,
        Dez\Core\Request,
        Dez\Error\Error as ErrorMessage,
        Dez\Core\Message,
        Dez\Utils,
        Dez\Web\Asset,
        Dez\Web\Layout,
        Dez\Utils\HTML,
        Dez\Auth\Web;

    class IndexController extends Controller {

        public function beforeExecute() {
            Layout::instance()->setName( 'start' );
            $webAuth    = Web::instance();
            $webAuth->authenticate( Request::instance()->cookie( 'uni_key', -1 ) );
            \Dez::app()->attach( 'webAuth', $webAuth );
        }

        public function homeAction() {
            Message::info( 'Главная страница' );
        }

        public function loginAction() {

            $auth = \Dez::app()->webAuth;

            if( $auth->id() > 0 ) {
                ErrorMessage::notify( 'Вы уже выторизированы. Сначала нужно '. HTML::a( adminUrl( 'index:logout' ), 'выйти' ) );
                $this->redirect( adminUrl( 'index:home' ) );
            } else {
                return $this->render( 'auth/login', [] );
            }

        }

        public function loginPostAction() {
            $auth = \Dez::app()->webAuth;
            $data = array(
                'email'     => $this->request->post( 'email', null ),
                'password'  => $this->request->post( 'password', null ),
            );
            try {
                $auth->login( array( $data['email'], $data['password'] ) );
                Message::success( 'Вы ('. $data['email'] .') успешно авторизированы' );
                $this->redirect( adminUrl( 'index:home' ) );
            } catch( \Exception $e ) {
                ErrorMessage::critical( $e->getMessage() );
                $this->redirect( adminUrl( 'index:login' ) );
            }
        }

        public function logoutAction() {
            \Dez::app()->auth->logout();
            Message::success( 'Вы успешно вышли из системы' );
            $this->redirect( adminUrl( 'index:login' ) );
        }

        public function processAction ( $controller = null, $action = null, $method = null ) {
dump('asd');
            if( $controller != 'index' ) {

                Layout::instance()->setName( 'index' );
                $auth = \Dez::app()->webAuth;
                //dump($auth->has('aaa'));
                if( ! $auth->id() ) {
                    ErrorMessage::warning( 'Авторизируйтесь' );
                    $this->redirect( adminUrl( 'index:login' ) ); die;
                }
//                else if( ! $auth->has( 'DEZ_ADMIN_PANEL' ) ) {
//                    $auth->logout();
//                    ErrorMessage::warning( 'Не достаточно прав' );
//                    $this->redirect( adminUrl( 'index:login' ) );
//                }

            }

            try {
                $moduleName = \Dez::app()->action->getWrapperRoute()->getModuleName();
                return $this->forward( $controller, $action . ucfirst( $method ), [], $moduleName );
            } catch( \Exception $e ) {
                ErrorMessage::warning( $e->getMessage() );
            }

        }

        public function processPostAction ( $controller = null, $action = null ) {
            return $this->processAction( $controller, $action, 'post' );
        }

    }