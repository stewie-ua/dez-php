<?php

    use \Sy\Core,
        \Sy\Common\Validator,
        \Sy\Error\Error,
        \Sy\Utils;

    class CommonController extends Core\Controller {

        public function uploadImageAction() {
            $model = $this->getModel( 'file' );

            $this->response->setType( 'json' );
            $this->response->setHeader( 'Content-type', 'application/json' );

            try {
                $file = $model->uploadImage();
                $response = [ 'filelink' => $file ];
            } catch ( \Exception $e ) {
                $response = [ 'error' => $e->getMessage() ];
            }

            return json_encode( $response, JSON_PRETTY_PRINT );
        }
        
        public function leftmenuAction() {
            return $this->render( 'inner/leftmenu', [] );
        }

    }