<?php

    use \Dez\Core,
        \Dez\Common\Validator,
        \Dez\Error\Error,
        \Dez\Utils\NumConv,
        \Dez\Utils\Crypt;

    class LinkController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'index' );
        }

        public function indexAction() {

            $auth       = \Dez::app()->auth;

            if( $auth->isLogged() ) {
                if( $this->request->isPost() ) {
                    $postData   = $this->request->post( 'data', [] );
                    $row        = $this->getModel( 'url' )->addUrl( $postData );
                    if( ( $itemId = $row->id() ) <= 0 ) {
                        $postData['author_id']  = $auth->id();
                        $row    = $this->getModel( 'url' )->findItem( $postData );
                        $itemId = $row->id();
                    }
                    $this->redirect( url( 'short-link/itemId'. $itemId ) );
                }
            } else {
                Error::critical( 'Авторизируйтесь для добавления' );
            }

            return $this->render( 'link/form', [] );
        }

        public function itemAction( $id = 0 ) {
            return $this->render( 'link/item', [
                'item'  => \DB\Url::instance()->findPk( $id )
            ] );
        }

        public function gotoAction( $id ) {
            $item = $this->getModel( 'url' )->getItem( NumConv::instance()->decode( $id ) );
            $this->redirect( $item->getUrl() );
        }

    }