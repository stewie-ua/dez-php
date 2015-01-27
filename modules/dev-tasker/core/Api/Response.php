<?php

    namespace Tasker\Api;

    class Response {

        static public function error( $data, $code = -1 ) {
            return [
                'success'   => false,
                'error'     => $data,
                'code'      => $code
            ];
        }

        static public function success( $data ) {
            return [
                'success'   => true,
                'content'   => $data
            ];
        }

        static public function tokenError() {
            return static::error( 'BAD ACCESS_TOKEN', 100 );
        }

    }