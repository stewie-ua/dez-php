<?php

    use \Sy\Core,
        \Sy\Common\Validator,
        \Sy\Error\Error;

    class ToolController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'index' );
            $this->response->set( 'left', $this->render( 'tool/common/left_menu', array() ) );
        }

        public function listAction() {
            return $this->render( 'tool/list', array(
                'tools'  => \DB\Tool::findAll()
            ) );
        }

        public function loadToolAction( $id = 0 ) {
            $tool = \DB\Tool::instance()->findPk( $id );
            if( $tool->id() > 0 ) {
                $this->redirect( $tool->getUrl() );
            } else {
                return $this->forward( 'index/pageUnderConstruction' );
            }
        }

        public function addAction() {
            $data = [];
            if( $this->request->isPost() ) {
                $data   = $this->request->post( 'tool', [] );
                $model  = $this->getModel( 'tool' );
                $tool   = $model->addTool( $data );
                if( $tool->id() > 0 ) {
                    $this->redirect( 'tools/item/'. $tool->id() );
                }
            }
            return $this->render( 'tool/form', array(
                'data'  => $data
            ) );
        }

    }