<?php

    namespace Dez\Response\Format;

    class Html extends FormatterAbstract {

        public function process() {
            $this->response->setHeader( 'Content-type', 'text/html' );
        }

    }