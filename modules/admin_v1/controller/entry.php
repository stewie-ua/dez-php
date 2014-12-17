<?php

    use Sy\Core;

    use Sy\Core\Message,
        Sy\Error\Error as ErrorMessage,
        Sy\Utils,
        Sy\Helper\Debug;

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
            $auth = \Sy::app()->auth;
            if( ! $auth->access( AUTH_ADMIN ) ) {
                ErrorMessage::warning( 'Авторизируйтесь' );
//                $this->redirect( url( 'admin/auth' ) );
            } else {
                Message::success( 'Добро пожаловать в '. Utils\HTML::tag( 'b', 'DezAdmin' ) );
//                $this->redirect( url( 'admin/index/dashboard' ) );
            }
        }

    }