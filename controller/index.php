<?php

    use Dez\Controller\Controller;

    class IndexController extends Controller {

        public function indexAction( ) {
            return __METHOD__ . '<br />' . __FILE__;
        }

    }