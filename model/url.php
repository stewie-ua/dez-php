<?php

    use \Dez\Core,
        \Dez\Error\Error,
        \Dez\Common\Validator;

    class UrlModel extends Core\Model {

        public function addUrl( array $data = [] ) {
            $item = \DB\Url::insert( [
                'url'       => $data['url'],
                'author_id' => \Dez::app()->auth->id(),
                'created'   => date( 'Y-m-d H:i:s' )
            ], true );
            return $item;
        }

        public function getItem( $id ) {
            return \DB\Url::instance()->findPk( $id );
        }

        public function findItem( array $data = [] ) {
            return \DB\Url::instance()
                ->filterByUrl( $data['url'] )
                ->filterByAuthorId( $data['author_id'] )
                ->findOne();
        }

    }