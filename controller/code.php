<?php

use \Dez\Core,
    \Dez\Common\Validator,
    \Dez\Error\Error,
    \Dez\Utils\NumConv,
    \Dez\Utils\Crypt;

class CodeController extends Core\Controller {

    public function __construct() {
        parent::__construct();
        $this->response->setLayout( 'index' );
        $this->response->set( 'left', $this->render( 'code/common/left_menu', array(
            'latest'    => \DB\Code::instance()->orderByAddedAt( 'DESC' )->limit( 0, 5 )->find()
        ) ) );
    }

    public function listAction(){
        $page   = $this->request->get( 'page', 0 );
        $codes  = \DB\Code::instance()->orderByAddedAt( 'DESC' )->pagi( $page, 36 )->find();
        return $this->render( 'code/list', array(
            'items'  => $codes
        ) );
    }

    public function itemAction( $codeId = null ) {
        $codeId         = NumConv::instance()->decode( $codeId );
        $model          = $this->getModel( 'code' );
        $codeItem       = $model->getCode( $codeId );

        if( $this->request->isPost() ) {
            $accessPassword = Crypt::instance()->encode( md5( $this->request->post( 'password' ) ), session_id() );
            $this->redirect( url( null, [ 'access-password' => $accessPassword ] ) );
        }

        if( $codeItem->id() > 0 ) {
            return $this->render( 'code/item', array(
                'item'  => $codeItem,
                'mime'  => array(
                    'php'	=> 'text/x-php',
                    'js'	=> 'text/javascript',
                    'html'	=> 'text/html',
                    'css'	=> 'text/css'
                )
            ) );
        } else {
            Error::warning( 'Access denied' );
            return $this->render( 'code/enter-password', array() );
        }
    }

    public function addAction() {
        $data = [];
        if( $this->request->isPost() ) {
            $data   = $this->request->post( 'code', [] );
            $model  = $this->getModel( 'code' );
            $code   = $model->addCode( $data );
            if( $code->id() > 0 ) {
                Core\Message::success( 'Успешно добавлено #'. $code->id() );
                $this->redirect( 'k/'. NumConv::instance()->encode( $code->id() ) );
            }
        }
        return $this->render( 'code/form', array(
            'data'  => $data
        ) );
    }

}