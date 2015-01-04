<?php

    use Dez\Error\Error as ErrorMessage,
        Dez\Common\Validator,

        Dez\Auth\Access;

    class UsersModel extends Dez\Core\Model {

        public function save( $user ) {
            $postData = $this->request->post();
            if( ! empty( $postData ) ) {
                $user->setLevelAccess( Access::instance()->accessToString( $postData['access'] ) );
                $user->save();
            } else {
                ErrorMessage::warning( 'Empty data... =(' );
            }
        }

    }