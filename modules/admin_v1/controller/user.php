<?php

    use \Sy\Core,
        \Sy\Common\Validator,
        \Sy\Error\Error,
        \Sy\Utils\NumConv,
        \Sy\Utils\Crypt;

    class UserController extends Core\Controller {

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