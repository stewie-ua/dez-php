<?php

    use Dez\Error\Error as ErrorMessage,
        Dez\Common\Validator;

    class UsersModel extends Dez\Core\Model {

        public function save( $user ) {
            $postData = $this->request->post( 'user', [] );
            if( ! empty( $postData ) ) {
                $user->bind( $postData )->save();
            } else {
                ErrorMessage::warning( 'Ебать! Ошибка...' );
            }
        }

    }