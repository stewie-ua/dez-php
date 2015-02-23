<?php

    use Dez\Controller\Controller,
        Dez\Response\Response,
        Dez\Error\Error as ErrorMessage,
        Dez\Core\Message,
        Dez\Utils,
        Dez\Web\Asset,
        Dez\Web\Layout,
        Dez\Utils\HTML;

    class IndexController extends Controller {

        public function beforeExecute() {
            Layout::instance()->setName( 'start' );
        }

        public function homeAction() {
            Message::info( 'Главная страница' );
        }

        public function loginAction() {

            $auth = \Dez::app()->auth;

            if( $auth->isLogged() ) {
                ErrorMessage::notify( 'Вы уже выторизированы. Сначала нужно '. HTML::a( adminUrl( 'index:logout' ), 'выйти' ) );
                $this->redirect( adminUrl( 'index:home' ) );
            } else {
                return $this->render( 'auth/login', [] );
            }

        }

        public function loginPostAction() {
            $auth = \Dez::app()->auth;
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

            if( $controller != 'index' ) {

                Layout::instance()->setName( 'index' );
                $auth = \Dez::app()->auth;
                //dump($auth->has('aaa'));
                if( ! $auth->isLogged() ) {
                    ErrorMessage::warning( 'Авторизируйтесь' );
                    $this->redirect( adminUrl( 'index:login' ) ); die;
                } else if( ! $auth->has( 'DEZ_ADMIN_PANEL' ) ) {
                    $auth->logout();
                    ErrorMessage::warning( 'Не достаточно прав' );
                    $this->redirect( adminUrl( 'index:login' ) );
                }

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