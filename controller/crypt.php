<?php

    use \Dez\Core,
        \Dez\Common\Validator,
        \Dez\Error\Error,
        \Dez\Utils\NumConv,
        \Dez\Utils\Crypt;

    class CryptController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'index' );
        }

        public function indexAction() {
            $data   = $this->request->post( 'crypt', [] );
            $result = null;

            if( $this->request->isPost() ) {
                $crypt      = Crypt::instance();
                $key        = isset( $data['key'] ) ? $data['key'] : 1;
                $result     = $data['mode'] == 'encode'
                    ? $crypt->encode( $data['text'], $key )
                    : $crypt->decode( $data['text'], $key );
            }

            return $this->render( 'crypt/form', [
                'data'      => $data,
                'result'    => $result
            ] );
        }

    }