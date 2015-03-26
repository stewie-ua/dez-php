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
            \Dez::app()->attach( 'auth', Web::instance() );
        }

        public function homeAction() {
            Message::info( 'Главная страница' );
            return __METHOD__;
        }

        public function loginAction() {

            $auth = \Dez::app()->auth;

            if( $auth->id() > 0 ) {
                ErrorMessage::notify( 'Вы уже выторизированы. Сначала нужно '. HTML::a( adminUrl( 'index:logout' ), 'выйти' ) );
                $this->redirect( adminUrl( 'index:home' ) );
            } else {
                return $this->render( 'auth/login', [] );
            }

        }

        public function loginPostAction() {
            return __METHOD__;
//            $auth = \Dez::app()->auth;
//            $data = array(
//                'email'     => $this->request->post( 'email', null ),
//                'password'  => $this->request->post( 'password', null ),
//            );
//            try {
//                $auth->login( array( $data['email'], $data['password'] ) );
//                Message::success( 'Вы ('. $data['email'] .') успешно авторизированы' );
//                $this->redirect( adminUrl( 'index:home' ) );
//            } catch( \Exception $e ) {
//                ErrorMessage::critical( $e->getMessage() );
//                $this->redirect( adminUrl( 'index:login' ) );
//            }
        }

        public function logoutAction() {
            \Dez::app()->auth->logout();
            Message::success( 'Вы успешно вышли из системы' );
            $this->redirect( adminUrl( 'index:login' ) );
        }

        public function processAction ( $controller = null, $action = null, $method = null ) {

            if( $controller != 'index' ) {

                Layout::instance()->setName( 'index' );
                $auth = \Dez::app()->auth;

                if( ! $auth->id() ) {
                    ErrorMessage::warning( 'Авторизируйтесь' );
                    $this->redirect( adminUrl( 'index:login' ) ); die;
                } else if( ! $auth->has( 'DEZ_ADMIN_PANEL' ) ) {
                    $auth->logout();
                    ErrorMessage::warning( 'Не достаточно прав' );
                    $this->redirect( adminUrl( 'index:login' ) );
                }

            }

            try {
                if( $method == 'post' ) {
                    dump(123);
                }
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