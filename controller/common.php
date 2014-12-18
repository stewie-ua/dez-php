<?php

    use Dez\Core,
        Dez\Common\Validator,
        Dez\Utils,
        Dez\Controller\Controller,
        Dez\Response\Response;

    class CommonController extends Controller {

        public function uploadImageAction() {
            $model = $this->getModel( 'file' );
            Response::instance()->setFormat( Response::RESPONSE_JSON );
            try {
                $file = $model->uploadImage();
                $response = [ 'filelink' => $file ];
            } catch ( \Exception $e ) {
                $response = [ 'error' => $e->getMessage() ];
            }
            return $response;
        }
        
        public function leftmenuAction() {
            return $this->render( 'inner/leftmenu', [] );
        }

    }