<?php

    namespace Sy\Core;

    use Sy\Error\Exception,
        Sy\Utils;

    class Action extends Object {

        use SingletonTrait;

        static protected
            $controllerInstances    = [];

        private
            $router     = null,
            $request    = null;

        protected function init( $router = null, $request = null ) {
            if( $router instanceOf Router && $request instanceOf Request ) {
                $this->router   = $router;
                $this->request  = $request;
            } else {
                throw new Exception\InvalidArgs( __CLASS__ .' Bad params pass to init' );
            }
        }

        public function setRequest( Request $request ) {
            $this->request   = $request; return $this;
        }

        public function getControllerInstance( $name = null, $moduleName = false ) {
            $hash = md5( $name . $moduleName );

            if( ! isset( static::$controllerInstances[$hash] ) ) {
                if( ! $moduleName ) {
                    $controllerDirectory = APP_PATH . DS .'controller';
                } else {
                    $controllerDirectory    = APP_PATH . DS .'modules'. DS . $moduleName . DS .'controller';
                    $bootstrapFile          = APP_PATH . DS .'modules'. DS . $moduleName . DS .'bootstrap.php';
                    if( file_exists( $bootstrapFile ) ) include_once $bootstrapFile;
                }

                $controllerFile = $controllerDirectory . DS . $name . '.php';
                if( ! file_exists( $controllerFile ) ) {
                    throw new Exception\RuntimeError( 'Controller file not found ['. Utils\HTML::tag( 'b', $controllerFile ) .']' );
                }

                include_once $controllerFile;

                $controllerClassName = ucfirst( $name ) .'Controller';
                if( ! class_exists( $controllerClassName ) ) {
                    throw new Exception\RuntimeError( 'Controller class not found ['. Utils\HTML::tag( 'b', $controllerClassName ) .']' );
                }

                static::$controllerInstances[$hash] = new $controllerClassName();
            }

            return static::$controllerInstances[$hash];
        }

        public function executeAction( Controller $controllerInstance, $action = null, array $methodArgs = [] ) {
            $actionName         = $action .'Action';
            if( $controllerInstance->hasMethod( $actionName ) ) {
                $controllerInstance->beforeExecute();
                $content    = call_user_func_array( array( $controllerInstance, $actionName ), $methodArgs );
                $controllerInstance->afterExecute();
                return $content;
            } else {
                throw new Exception\RuntimeError( $controllerInstance->getClassName() . '::' . $actionName .'() Calling unknown method' );
            }
        }

        public function execute() {
            $route          = $this->request->get( 'r', 'index/index' );
            $routeResults   = $this->router->getResult( $route, $this->request->getMethod() );
            $output         = [];

            if( count( $routeResults ) > 0 ) {
                foreach( $routeResults as $routeResult ) {
                    $output[] = $this->_execute( $routeResult );
                }
            }

            return join( '<br />', $output );
        }

        private function _execute( $routeResult ) {
            $controller     = $this->getControllerInstance( $routeResult->controllerName, $routeResult->moduleName );
            return $this->executeAction( $controller, $routeResult->actionName, $routeResult->params );
        }

    }