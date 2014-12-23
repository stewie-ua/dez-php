<?php

    namespace Dez\Controller;

    use Dez\Core\Object,
        Dez\View\View,
        Dez\Web\Layout,
        Dez\Error\Exception\RuntimeError,
        Dez\Utils\HTML;

    class Controller extends Object {

        protected
            $relativePath   = null,
            $view           = null;

        public function __construct() {
            $reflection         = ( new \ReflectionClass( $this->getClassName() ) );
            $this->relativePath = realpath( dirname( $reflection->getFileName() ) . '/..' );
            if( ! $this->relativePath )
                throw new RuntimeError( $this->getClassName() .' '. HTML::tag( 'b', 'Bad script name from ReflectionClass' ) );
            $this->view         = clone View::instance()->setPath( $this->relativePath .'/view' );
        }

        public function beforeExecute() {}

        public function afterExecute() {}

        public function render( $template = null, array $data = [] ) {
            $data['layout'] = Layout::instance();
            return $this->view->render( $template, $data );
        }

        public function redirect( $url = null ) {

        }

    }