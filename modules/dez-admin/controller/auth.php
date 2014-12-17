<?php

    use Dez\Core;

    use Dez\Core\Message,
        Dez\Error\Error as ErrorMessage;

    class AuthController extends Core\Controller {

        public function beforeExecute() {
            $this->response->setLayout( 'start_page' );
        }

        public function loginPageAction() {
            dump( $this->getView() );
            return $this->render( 'auth/login', [] );
        }

    }