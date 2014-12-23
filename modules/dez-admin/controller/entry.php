<?php

    use Dez\Controller\Controller,
        Dez\Error\Error as ErrorMessage,
        Dez\Utils,
        Dez\Web\Asset,
        Dez\Web\Layout;

    class EntryController extends Controller {

        public function beforeExecute() {

        }

        public function afterExecute() {

        }

        public function indexAction() {
            $auth = \Dez::app()->auth;
            if( ! $auth->access( AUTH_ADMIN ) ) {
                ErrorMessage::warning( 'Авторизируйтесь' );
            }
            return $this->render( 'auth/login', [] );
        }

    }