<?php

    use Sy\Core;

    use Sy\Core\Message;

    class AdminController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'admin' );
        }

        public function indexAction() {
            Message::info( 'Главная страница админ-панели' );
            return __METHOD__;
        }

        public function userslistAction() {
            return __METHOD__;
        }

        public function profileAction() {
            return __METHOD__;
        }

    }