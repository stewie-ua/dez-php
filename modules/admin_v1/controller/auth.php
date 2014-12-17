<?php

    use Sy\Core;

    use Sy\Core\Message,
        Sy\Error\Error as ErrorMessage;

    class AuthController extends Core\Controller {

        public function beforeExecute() {
            $this->response->setLayout( 'start_page' );
        }

        public function loginPageAction() {

        }

    }