<?php

    use Dez\Controller\Controller;

    class UserController extends Controller {

        protected
            $requestMethod = 'NONE';

        public function beforeExecute() {
            $this->requestMethod    = strtoupper( $this->request->method );
        }

        public function methodRunAction( $methodName = null ) {
            return [ $methodName, $this->requestMethod, $this->request ];
        }

    }