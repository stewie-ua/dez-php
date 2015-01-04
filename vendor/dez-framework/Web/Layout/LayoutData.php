<?php

    namespace Dez\Web\Layout;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,
        Dez\Web\Layout,
        Dez\Utils\HTML;

    class LayoutData extends Object {

        use SingletonTrait;

        protected
            $data   = [];

        protected function init( array $data = [] ) {
            $this->data     = $data;
        }

        public function getTitle() {
            return join( \Dez::app()->config->path( 'main.title_separator' ), $this->get( Layout::KEY_NAME_TITLE, [] ) );
        }

        public function getDescription() {
            return HTML::description( join( ' ', $this->get( Layout::KEY_NAME_DESCR, [] ) ) ) . "\n";
        }

        public function getKeyword() {
            return HTML::keyword( join( ', ', $this->get( Layout::KEY_NAME_KEYWORD, [] ) ) ) . "\n";
        }

        public function get( $key, $default = null ) {
            return isset( $this->data[$key] ) ? $this->data[$key] : $default;
        }

    }