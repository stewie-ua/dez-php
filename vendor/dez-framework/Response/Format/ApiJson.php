<?php

    namespace Dez\Response\Format;

    class ApiJson extends FormatterAbstract {

        public function process() {
            $this->response->setHeader( 'Content-type', 'application/json' );
            $response                   = $this->response->getBody();
            $response                   = ! is_array( $response ) ? [ $response ] : $response;
            $response['http_code']      = $this->response->getCode();
            $response['execute_time']   = \Dez::getTimeDiff();
            $response['memory_use']     = \Dez::getMemoryUse();
            $this->response->setBody( json_encode( $response, JSON_PRETTY_PRINT ) );
        }

    }