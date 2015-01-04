<?php

    use Dez\Controller\Controller,
        Dez\Core,
        Dez\Auth\Access,
        Dez\Common\Validator,
        Dez\Error\Error as ErrorMessage,
        Dez\Utils\NumConv,
        Dez\Utils\Crypt;

    class UsersController extends Controller {

        public function indexAction() {
            $users = \DB\User::instance()->orderById( 'DESC' )->pagi( $this->request->get( 'page', 1 ), 3 )->find();
            return $this->render( 'user/list', [
                'users'     => $users
            ] );
        }

        public function profileAction() {
            $userId = $this->request->get( 'id', 0 );
            if( $userId > 0 ) {
                $user = \DB\User::instance()->findPk( $userId );
                if( $user->id() > 0 ) {
                    return $this->render( 'user/item', [
                        'user'          => $user,
                        'accessList'    => \DB\Access::findAll(),
                        'authAccess'    => Access::instance()
                    ] );
                }
                ErrorMessage::critical( 'UserID: '. $userId .' not found' );
            }
            ErrorMessage::critical( 'User can not be found' );
        }

        public function profilePostAction() {

            $userId = $this->request->get( 'id', 0 );

            if( $userId > 0 ) {
                $user = \DB\User::instance()->findPk( $userId );
                if( $user->id() > 0 ) {
                    $usersModel = $this->model( 'users' );
                    $usersModel->save( $user );
                } else {
                    ErrorMessage::critical( 'Bad request [User ID wrong]' );
                }
            } else {
                ErrorMessage::critical( 'Bad request [User ID wrong]' );
            }

            $this->redirect( url() );
        }

        public function commentAction() {
            return print_r( func_get_args(), true );
        }

    }