<?php

    use \Sy\Core,
        \Sy\Common\Validator,
        \Sy\Error\Error;

    class PostController extends Core\Controller {

        public function __construct() {
            parent::__construct();
            $this->response->setLayout( 'index' );
            $model = $this->getModel( 'post' );
            $this->response->set( 'left', $this->render( 'post/common/left_menu', array(
                'tags'  => $model->getTagCloud()
            ) ) );
        }

        public function listAction() {
            $this->response->addTitle( 'Публикации' );
//            Core\Message::success( 'Публикации' );
            return $this->render(
                'post/list', array(
                    'items' => $this->getModel( 'post' )->getPostList( $this->request->get( 'p', 1 ) )
                )
            );
        }

        public function listByTagsAction( $tagName = null, $page = 1 ) {
            $this->response->addTitle( 'Публикации по тегу - '. $tagName );
            $where = [ 'tags.name', $tagName ];
            return $this->render( 'post/list', array( 'items' => $this->getModel( 'post' )->getPostList( $page, $where ) ) );
        }

        public function addAction() {
            $this->response->addTitle( 'Добавление публикации' );
            return $this->render( 'post/add', [] );
        }

        public function addPostAction() {
            $model  = $this->getModel( 'post' );
            $post   = $model->addAction();
            if( $post->id() > 0 ) {
                Core\Message::success( 'Публикация #'. $post->id() .' добавлена' );
                $this->redirect( 'posts' );
            } else {
                return $this->render( 'post/add', array( 'data'  => $this->request->post( 'post' ) ) );
            }
        }

    }