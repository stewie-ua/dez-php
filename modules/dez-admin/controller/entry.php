<?php

    use Dez\Core;

    use Dez\Core\Message,
        Dez\Error\Error as ErrorMessage,
        Dez\Utils,
        Dez\Helper\Debug;

    class EntryController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'admin' );
        }

        public function beforeExecute() {
            $templateDirectory = realpath( __DIR__ . DS . '..' . DS . 'view' );
            $this->getView()
                ->setDirectory( $templateDirectory )->setTemplateExt( 'php' );
            $this->getResponse()
                ->setDirectory( $templateDirectory )->setTemplateExt( 'php' );
        }

        public function afterExecute() {

        }

        public function indexAction() {

            return $this->render( 'auth/login', [] );

            $auth = \Dez::app()->auth;
            if( ! $auth->access( AUTH_ADMIN ) ) {
                ErrorMessage::warning( 'Авторизируйтесь' );
            }
        }

    }