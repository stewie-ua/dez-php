<?php
	
	namespace Sy\Core;
	
	class Response {

        use SingletonTrait, HasDataTrait;
		
		private $view               = null,

                $headers 			= [],
				$data				= [],
				$documentType		= 'default',
				$layout			    = 'layout',
				$titleSeparator 	= null,
				$title 			    = [];
		
		protected function init(){
			$this->setTitleSeparator( \Sy::cfg()->path( 'base.titleSeparator' ) );
            $this->view     = new View( APP_PATH . DS . 'view' );
		}


		public function getTitleSeparator(){
			return $this->titleSeparator;
		}

		public function setTitleSeparator( $separator = null ){
			$this->titleSeparator = $separator;
		}

		public function getTitle(){
			return $this->title;
		}

		public function setTitle( $title ){
			$this->title = [ $title ];
		}

		public function addTitle( $title ){
			$this->title[] = $title;
		}


		public function getType(){
			return $this->documentType;
		}
		
		public function setType( $type ){
			$this->documentType = $type;
		}
		
		public function getLayout(){
			return $this->layout;
		}
		
		public function setLayout( $layout ){
			$this->layout = $layout;
		}

        public function setDirectory( $directory = null ) {
            $this->view->setDirectory( $directory );
            return $this;
        }

        public function getTemplateExt() {
            $this->view->getTemplateExt();
        }

        public function setTemplateExt( $templateExt = null ) {
            $this->view->setTemplateExt( $templateExt );
        }
		
		protected function & getData() {
            return $this->data;
        }
		
		public function render(){
			$this->_beforeRender();
			return $this->view->render( $this->getLayout(), $this->getData() );
		}
		
		public function setHeader( $name, $value ){
			$this->headers[] = [ $name, $value ];
		}
		
		public function & getHeaders(){
			return $this->headers;
		}
		
		public function sendHeaders(){
			if( ! headers_sent() )
                foreach( $this->headers as $header )
                    header( $header[0] .': '. $header[1], false );
		}

		private function _beforeRender() {
			$this->set( 'exec_time',    \Sy::getTimeDiff() );
			$this->set( 'memory_use',   \Sy::getMemoryUse() );
			$this->set( 'title',        join( $this->getTitleSeparator(), $this->getTitle() ) );
		}
		
	}