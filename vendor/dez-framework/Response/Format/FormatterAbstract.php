<?php

    namespace Dez\Response\Format;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait;

    abstract class FormatterAbstract extends Object {

        use SingletonTrait;

        /**
         * @var $response \Dez\Response\Response
         */
        protected
            $response = null;

        protected function init( $response = null ) {
            $this->response = $response;
        }

        abstract public function process();

    }