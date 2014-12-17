<?php

    use \Dez\Core,
        \Dez\Common\Validator,
        \Dez\Error\Error,
        \Dez\Utils\NumConv,
        \Dez\Utils\Crypt;

    class UserController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'index' );
        }

        public function listAction() {
            $users = \DB\User::instance()->orderById( 'DESC' )->pagi( $this->request->get( 'page', 1 ), 20 )->find();
            return $this->render( 'user/list', [
                'users'     => $users
            ] );
        }

        public function userCommentAction() {
            return print_r( func_get_args(), true );
        }

    }