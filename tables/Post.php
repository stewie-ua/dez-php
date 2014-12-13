<?php

    namespace DB;

    use \Sy\ORM\Entity;

    class Post extends Entity\Table {

        static protected $tableName     = 'posts';

        public function getFullPosts( $where = [], $page = 1 ) {
            $qb             = $this->getQueryBuilder();
            $groupConcat    = $qb->func( 'groupconcat', 'tags.name post_tags' );
            $qb
                ->table( 'posts' )
                ->select( [ 'posts.*', 'system_auth.email author_login', $groupConcat ] )
                ->innerJoin( 'xref_tags', 'posts', [ 'post_id', 'id', '=' ] )
                ->innerJoin( 'tags', 'xref_tags', [ 'id', 'tag_id', '=' ] )
                ->innerJoin( 'system_auth', 'posts', [ 'id', 'author_id', '=' ] )
                ->group( 'id' )
                ->order( array( 'id', 'desc' ) );

            if( ! empty( $where ) ) {
                $qb->where( $where );
            }

            $this->pagi( $page, 5 );
            $this->setStmt( $this->getConnection()->query( $qb->query() ) );
            return $this;
        }

    }