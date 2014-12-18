<?php

    namespace Dez\Core;

    use Dez\Error\Exception,
        Dez\Utils,
        Dez\Controller\Controller;

    class Action extends Object {

        use SingletonTrait;

        static protected
            $controllerInstances    = [];

        private
            $router         = null,
            $request        = null,
            $wrapperRoute   = null;

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
            $route                  = $this->request->get( 'r', 'index/index' );
            $this->wrapperRoute     = $this->router->getResult( $route, $this->request->getMethod() );

            $controller             = $this->getControllerInstance( $this->wrapperRoute->controllerName, $this->wrapperRoute->moduleName );
            return $this->executeAction( $controller, $this->wrapperRoute->actionName, $this->wrapperRoute->params );

        }

    }