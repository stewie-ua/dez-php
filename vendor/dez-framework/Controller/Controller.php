<?php

    namespace Dez\Controller;

    use Dez\Core\Object,
        Dez\Core\Action,
        Dez\View\View,
        Dez\Core\Request,
        Dez\Web\Layout,
        Dez\Error\Exception\RuntimeError,
        Dez\Utils\HTML;

    class Controller extends Object {

        protected
            $relativePath   = null,
            $view           = null,

            /**
             * @var \Dez\Core\Request
            */
            $request        = null;

        static protected
            $models         = [];

        public function __construct() {
            $reflection         = ( new \ReflectionClass( $this->getClassName() ) );
            $this->relativePath = realpath( dirname( $reflection->getFileName() ) . '/..' );
            if( ! $this->relativePath )
                throw new RuntimeError( $this->getClassName() .' '. HTML::tag( 'b', 'Bad script name from ReflectionClass' ) );
            $this->view         = clone View::instance()->setPath( $this->relativePath .'/view' );
            $this->request      = Request::instance();
        }

        public function beforeExecute() {}

        public function afterExecute() {}

        public function forward( $controller, $actionName, array $params = [], $moduleName = false ) {
            $action         = \Dez::app()->action;
            if( is_string( $controller ) ) $controller = $action->getControllerInstance( $controller, $moduleName );
            return $action->executeAction( $controller, $actionName, $params );
        }

        /**
         * @param string $name
         * @throws RuntimeError
        */

        public function model( $name = null ) {
            $hash = md5( $name );
            if( ! isset( static::$models[$hash] ) ) {
                $modelFile = $this->relativePath . "/model/{$name}.php";
                if( ! file_exists( $modelFile ) ) {
                    throw new RuntimeError( "Model file not found '{$modelFile}'" );
                }
                include_once $modelFile;
                $modelClassName = ucfirst( $name ) .'Model';
                if( ! class_exists( $modelClassName ) ) {
                    throw new RuntimeError( "Model class not found '{$modelClassName}'" );
                }
                static::$models[$hash] = new $modelClassName();
            }
            return static::$models[$hash];
        }

        public function render( $template = null, array $data = [] ) {
            $data['layout'] = Layout::instance();
            return $this->view->render( $template, $data );
        }

        public function redirect( $url = null ) {
            \dez::app()->redirect( $url );
        }

    }