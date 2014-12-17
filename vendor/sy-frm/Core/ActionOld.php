<?php

	namespace Sy\Core;

	class ActionOld {

        static private
			$_anonymousFunctions 	= array(),
			$_instances 			= array();

        public function registerHandlerOfRoute( $route, $handler ){
            $route		= trim( $route, '/' );
			if( 1 == sizeOf( $handler ) && is_callable( $handler[0] ) ){
				$hash = md5( $route );
				if( empty( self::$_anonymousFunctions[$hash] ) ){
					self::$_anonymousFunctions[$hash] = $handler[0];
				}				
				\Sy::app()->router->addRule( $route, 'lambda', 'lambda' );
			}else if( 2 == count( $handler ) ){
				\Sy::app()->router->addRule( $route, $handler[0], $handler[1] );
			}
			return $this;
		}

        public function execute( Request $request ) {
			$route 		= isset( $request->get['r'] ) ? $request->get['r'] : null;
			$route		= trim( $route, '/' );
			$lambda 	= $this->_findLambdaFunction( $route );			
			if( $lambda <> false ){
				$result = \Sy::app()->router->getResult( $route, $request->method );
				$args 	= isset( $result['values'] ) ? $result['values'] : array();
				call_user_func_array( $lambda, $args );
			}else{
				$result 		= \Sy::app()->router->getResult( $route, $request->method );
                $args 			= isset( $result['values'] ) ? $result['values'] : array();
				$controllerName = $result['controller'];
				$methodName 	= $result['action'];
				try{
					$controllerInstance = $this->_getControllerInstance( $controllerName );
                    return $this->execAction( $controllerInstance, $methodName, $args );
				}catch( \Exception $e ){
					throw $e;
				}
			}			
		}

        public function getController( $name ) {
            return $this->_getControllerInstance( $name );
        }

        public function execAction( Controller $instance, $actionName, array $args = array() ) {
            $actionName .= 'Action';
            if( ! method_exists( $instance, $actionName ) ){
                throw new \Exception( \Sy::t( 'Method not exists ['. get_class( $instance ) .'::'. $actionName .']' ) );
            }
            $instance->beforeExecute();
            $content    = call_user_func_array( array( $instance, $actionName ), $args );
            $instance->afterExecute();
            return $content;
        }

        private function _getControllerInstance( $controllerName = null ){
			$hash = md5( $controllerName );
			if( $controllerName <> null ){
				if( empty( self::$_instances[$hash] ) ){
					$controllerFile 	= APP_PATH . DS . 'controller' . DS . $controllerName . '.php';
					if( ! file_exists( $controllerFile ) ){
						throw new \Exception( \Sy::t( 'Controller don`t exists ['. $controllerFile .']' ) );
					}
					include_once $controllerFile;
					$className 	= ucfirst( strtolower( $controllerName ) ) . 'Controller';
					if( ! class_exists( $className ) ){
						throw new \Exception( \Sy::t( 'Class don`t exists ['. $className .']' ) );
					}
					self::$_instances[$hash] = new $className();
				}				
				return self::$_instances[$hash];
			}else{
				return false;
			}
		}

        private function _findLambdaFunction( $route ){
			$hash = md5( $route );
			return isset( self::$_anonymousFunctions[$hash] ) ? self::$_anonymousFunctions[$hash] : false;
		}
		
	}