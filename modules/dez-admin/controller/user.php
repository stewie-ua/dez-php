<?php

    use Dez\Controller\Controller,
        Dez\Core,
        Dez\Common\Validator,
        Dez\Error\Error,
        Dez\Utils\NumConv,
        Dez\Utils\Crypt;

    class UserController extends Controller {

        public function listAction() {
            $users = \DB\User::instance()->orderById( 'DESC' )->pagi( $this->request->get( 'page', 1 ), 3 )->find();
            return $this->render( 'user/list', [
                'users'     => $users
            ] );
        }

        public function commentAction() {
            return print_r( func_get_args(), true );
        }

    }