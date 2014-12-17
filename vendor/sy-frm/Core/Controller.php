<?php
	
	namespace Sy\Core;
	
	class Controller extends Object {

        static private
			$models = array();

        protected
            $response,
            $request,
            $view;

        public function __construct(){
            $this->response = \Sy::app()->response;
            $this->request  = \Sy::app()->request;
            $this->view     = clone \Sy::app()->view;
        }

        public function beforeExecute() {}

        public function afterExecute() {}

        protected function render( $file, $data ) {
            return $this->view->render( $file, $data );
        }

        protected function forward( $path, array $args = array() ) {
            list( $controllerName, $actionName ) = explode( '/', $path );
            $action     = \Sy::app()->action;
            $instance   = $action->getControllerInstance( $controllerName );
            return $action->executeAction( $instance, $actionName, $args );
        }

        protected function getModel( $model_name = null ) {

			if( empty( $model_name ) ) {
				Error::critical( 'Set model name' ); return;
			}

			$model_name = preg_replace( '/[^0-9a-z_]/iu', '', $model_name );
			$model_name = ucfirst( strtolower( $model_name ) );

			$hash       = md5( $model_name );

			if( ! isset( self::$models[$hash] ) ) {
				self::$models[$hash] = $this->_createModel( $model_name );
			}

			return self::$models[$hash];

		}

        private function _createModel( $model_name ) {

			$model_file = APP_PATH . DS . 'model' . DS . strtolower( $model_name ) . '.php';

			if( ! file_exists( $model_file ) ) {
				Error::critical( 'Model file not found ['. $model_name .']' );
				return false;
			}

			include_once $model_file;

			$model_class = $model_name . 'Model';

			if( class_exists( $model_class ) == false ) {
				Error::critical( 'Model class not found ['. $model_class .']' );
				return false;
			}

			return new $model_class;

		}

        protected function redirect( $url ) {
            \Sy::app()->redirect( $url );
        }

	}