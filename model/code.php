<?php

    use \Sy\Core,
        \Sy\Error\Error,
        \Sy\Common\Validator,
        \Sy\Utils;

    class CodeModel extends Core\Model {

        public function getCode( $codeId = 0 ) {
            $shareLink              = $this->request->get( 'share-link', null );
            $accessPassword         = $this->request->get( 'access-password', null );

            $codeItem               = \DB\Code::instance()->findPk( $codeId );

            if( $codeItem->getPassword() != ''
                && (
                    $codeItem->getAuthorId() > 0
                    && $codeItem->getAuthorId() != \Sy::app()->auth->id()
                ) )
            {
                if( ! empty( $shareLink ) ) {
                    $parts = explode( ':', Utils\Crypt::instance()->decode( $shareLink, 'kill.li' ) );
                    if( isset( $parts[0] ) && isset( $parts[1] ) ) {
                        $codeId     = intval( $parts[0] );
                        $authorId   = intval( $parts[1] );
                        if( $codeItem->id() == $codeId && $codeItem->getAuthorId() == $authorId ) {
                            return $codeItem;
                        }
                    }
                } else if ( ! empty( $accessPassword ) ) {
                    $password = Utils\Crypt::instance()->decode( $accessPassword, session_id() );
                    if( $codeItem->getPassword() == $password ) {
                        return $codeItem;
                    } else {
//                        Error::critical( 'Password invalid' );
                    }
                }
                return \DB\Code::row();
            } else {
                return $codeItem;
            }

        }

        public function addCode( array $data = [] ) {

            $code = \DB\Code::row();

            $validator  = new Validator();
            $validator->attachData( $data );
            $validator->addRule(
                new Validator\Rule( 'title', 'notempty', null, 'Заголовок не может быть пустым' )
            );
            $validator->addRule(
                new Validator\Rule( 'source', 'notempty', null, 'Поле с кодом не может быть пустое' )
            );
            $validator->addRule(
                new Validator\Rule( 'password', 'callback', function( $data ){
                    return ( $data == '' || \Sy::app()->auth->isLogged() );
                }, 'Чтобы ввести пароль Вас нужно авторизироватся' )
            );
            $validator->run();

            if( $validator->isError() ) {
                Error::critical( join( '<br />', $validator->getErrors() ) );
            } else {
                $auth = \Sy::app()->auth;

                $code->setTitle( $data['title'] );
                $code->setLanguage( $data['language'] );
                $code->setSource( $data['source'] );

                if( $auth->isLogged() )
                    $code->setAuthorId( $auth->id() );
                if( ! empty( $data['password'] ) )
                    $code->setPassword( md5( $data['password'] ) );

                $code->setAddedAt( date( 'Y-m-d H:i:s' ) );

                $code->save();
            }

            return $code;
        }

    }