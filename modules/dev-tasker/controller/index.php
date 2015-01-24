<?php

    use Dez\Controller\Controller;

    class IndexController extends Controller {

        public function indexAction() {
            return true;
        }

        public function runAction( $controller, $action ) {
            return [$controller, $action];
        }

        public function notFoundAction() {
            return [ 'BAD REQUEST', func_get_args() ];
        }

    }