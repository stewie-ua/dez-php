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
            $request        = null;

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

        public function forward( $controllerName, $actionName, array $params = [], $moduleName = false ) {
            $action         = \Dez::app()->action;
            $controller     = $action->getControllerInstance( $controllerName, $moduleName );
            return $action->executeAction( $controller, $actionName, $params );
        }

        public function render( $template = null, array $data = [] ) {
            $data['layout'] = Layout::instance();
            return $this->view->render( $template, $data );
        }

        public function redirect( $url = null ) {
            \dez::app()->redirect( $url );
        }

    }