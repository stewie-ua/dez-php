<?php
	
	namespace Sy\Core;
	
	class Response{
		
		private $_headers 			= array(),
				$_vars				= array(),
				$_type				= 'default',
				$_layout			= 'layout',
				$_titleSeparator 	= null,
				$_title 			= array();
		
		public function __construct(){
			$baseCfg = \Sy::cfg( 'base' );
			$this->setSeparator( $baseCfg['titleSeparator'] );
		}

		// Title
		public function getSeparator(){
			return $this->_titleSeparator;
		}

		public function setSeparator( $separator = null ){
			$this->_titleSeparator = $separator;
		}

		public function getTitle(){
			return $this->_title;
		}

		public function setTitle( $title ){
			if( ! is_array( $title ) ){
				$title = array( $title );
			}
			$this->_title = $title;
		}

		public function addTitle( $title ){
			$this->_title[] = $title;
		}
		// End title part

		public function getType(){
			return $this->_type;
		}
		
		public function setType( $type ){
			$this->_type = (string) $type;
		}
		
		public function getLayout(){
			return $this->_layout;
		}
		
		public function setLayout( $layout_name ){
			$this->_layout = (string) $layout_name;
		}
		
		public function set( $name, $value ){		
			if( is_string( $name ) && ! empty( $value ) ){
				$this->_vars[$name] = $value;
			}			
		}

        public function get( $name ) {
            if( isset( $this->_vars[$name] ) ) {
                return $this->_vars[$name];
            }
            return null;
        }
		
		public function render(){
			$this->_beforeRender();
			return \Sy::app()->view->render( $this->_layout, $this->_vars );
		}
		
		public function setHeader( $name, $value ){
			$name 	= (string) $name;
			$value	= (string) $value;
			
			$this->_headers[] = array(
				'name' 	=> $name,
				'value'	=> $value
			);			
		}
		
		public function & getHeaders(){
			return $this->_headers;
		}
		
		public function sendHeaders(){
			if( ! headers_sent() ){
				foreach( $this->_headers as $header ){
					header( $header['name'] .': '. $header['value'], false );
				}
			}
		}

		private function _beforeRender() {
			$this->set( 'exec_time',    \Sy::getTimeDiff() );
			$this->set( 'memory_use',   \Sy::getMemoryUse() );
			$this->set( 'title',        join( $this->getSeparator(), $this->getTitle() ) );
		}
		
	}