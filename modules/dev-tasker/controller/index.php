<?php

    use Dez\Controller\Controller;

    class IndexController extends Controller {

        public function indexAction() {
            return true;
        }

        public function notFoundAction() {
            return [ 'BAD REQUEST' ];
        }

    }