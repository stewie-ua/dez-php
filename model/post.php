<?php

    use \Dez\Core,
        \Dez\Error\Error,
        \Dez\Common\Validator;

    class PostModel extends Core\Model {

        public function getPostList( $page = 1, $where = [] ) {
            return \DB\Post::instance()->getFullPosts( $where, $page );
        }

        public function getTagCloud() {
            $query = '
                SELECT
                    `tags`.`name`,
                    COUNT( `tags`.`id` ) AS `tag_rate`
                FROM `tags`
                INNER JOIN `xref_tags` ON `tags`.`id` = `xref_tags`.`tag_id`
                GROUP BY `tags`.`id`
                ORDER BY `tag_rate` DESC, RAND()
                LIMIT 0, 35
            ';
            $rows = array();
            $stmt = $this->db->query( $query );
            while( $row = $stmt->loadArray() ) { $rows[] = $row; }
            return $rows;
        }

        public function addAction() {

            $validator  = new Validator();
            $request    = \Dez::app()->request;
            $auth       = \Dez::app()->auth;
            $postData   = $request->post( 'post' );

            $post = \DB\Post::row();

            if( $auth->isLogged() ) {

                $validator->attachData( $postData );
                $validator->addRule(
                    new Validator\Rule( 'title', 'notempty', null, 'Поле "Заголовок" не может быть пустое' )
                );
                $validator->addRule(
                    new Validator\Rule( 'tags', 'notempty', null, 'Поле "Теги" не может быть пустое' )
                );
                $validator->run();

                if( $validator->isError() ) {
                    Error::critical( join( '<br />', $validator->getErrors() ) );
                } else {

                    $post
                        ->setAuthorId( $auth->id() )
                        ->setTitle( $postData['title'] )
                        ->setText( $postData['text'] )
                        ->setCreated( date( 'Y-m-d H:i:s' ) );
                    $post->save();

                    $postId = $post->id();

                    if( $postId > 0 ) {
                        $tags       = array_map( 'trim', explode( ',', strtolower( $postData['tags'] ) ) );
                        $tagStmt    = $this->db
                            ->prepareQuery( 'insert ignore into `tags` set `name` = :name' );
                        $xtagStmt   = $this->db
                            ->prepareQuery( 'insert ignore into `xref_tags` set `tag_id` = ?, `post_id` = ?' );
                        foreach( $tags as $tag ) {
                            $tag        = strtolower( $tag );
                            $tagStmt->execute( array( ':name' => $tag ) );
                            $tagId      = \DB\Tags::instance()->filterByName( $tag )->findOne()->id();
                            $xtagStmt->multiBind( array( $tagId, $postId ) )->execute();
                        }
                    } else {
                        Error::critical( 'Не удалось сохранить' );
                    }

                }

            } else {
                Error::critical( 'Авторизируйтесь для совершения данного действия' );
            }

            return $post;
        }

    }