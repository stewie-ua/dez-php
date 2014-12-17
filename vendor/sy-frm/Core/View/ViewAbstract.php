<?php

    namespace Sy\Core\View;

    abstract class ViewAbstract {

        protected
            $templateDirectory  = null,
            $templateExt        = null;

        public function __construct( & $templateDirectory, & $templateExt = 'php' ) {
            $this->templateDirectory    = & $templateDirectory;
            $this->templateExt          = & $templateExt;
        }

        protected function makeTemplateFile( $shortPath = null ) {
            $fullTemplateFile = $this->templateDirectory . DS . $shortPath .'.'. $this->templateExt;
            if( ! $this->fso()->isExistsFile( $fullTemplateFile ) ) {
                throw new \Exception( 'Template file not found [<b>'. $fullTemplateFile .'</b>]' );
            } else {
                return $fullTemplateFile;
            }
        }

        protected function fso() {
            static $fso;
            if( empty( $fso ) ) {
                $fso = new \Sy\Utils\FSO();
            }
            return $fso;
        }

        abstract public function render( $templateFile = null, array $templateData = array() );

    }