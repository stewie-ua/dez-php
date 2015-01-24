<?php

    use Dez\Controller\Controller;

    class SystemToolsController extends Controller {

        public function indexAction() {
            $toolName       = $this->request->get( 'name', false );
            $seflMethod     = $toolName . 'Action';
            if( ! $toolName || ! method_exists( $this, $seflMethod ) ) {
                return $this->render( 'system_tools/list' );
            } else {
                return $this->forward( $this, $toolName );
            }
        }

        public function requestEmulateAction() {
            return $this->render( 'system_tools/request_emulate', [] );
        }

    }