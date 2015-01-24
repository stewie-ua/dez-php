<?php

    namespace Dez\Response\Format;

    class Json extends FormatterAbstract {

        public function process() {
            $this->response->setHeader( 'Content-type', 'application/json' );
            $this->response->setBody( json_encode( $this->response->getBody(), true ) );
        }

    }