<?php

    use \Sy\Core,
        \Sy\Error\Error,
        \Sy\Common\Validator;

    class ToolModel extends Core\Model {

        public function addTool( array $data = [] ) {

            $validator  = new Validator();
            $auth       = \Sy::app()->auth;
            $tool       = \DB\Tool::row();

            if( $auth->isLogged() ) {

                $validator->attachData( $data );
                $validator->addRule( new Validator\Rule( 'title', 'notempty', null, 'Введите название' ) );
                $validator->addRule( new Validator\Rule( 'description', 'notempty', null, 'Введите описание' ) );
                $validator->addRule( new Validator\Rule( 'url', 'notempty', null, 'Введите URL' ) );
                $validator->run();

                if( $validator->isError() ) {
                    Error::critical( join( '<br />', $validator->getErrors() ) );
                } else {

                    $tool
                        ->setTitle( $data['title'] )
                        ->setDescription( $data['description'] )
                        ->setUrl( $data['url'] );
                    $tool->save();
                }

            } else {
                Error::critical( 'Авторизируйтесь для совершения данного действия' );
            }

            return $tool;
        }

    }