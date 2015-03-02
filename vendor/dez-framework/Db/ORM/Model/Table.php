<?php

    namespace Dez\ORM\Model;

    class Table extends TableAbstract {

        /**
         * @return QueryBuilder $builder
        */

        static public function query() {
            return QueryBuilder::instance( static::instance() );
        }

        static public function all() {
            return static::query()->find();
        }

        static public function one() {

        }

        static public function insert( array $data = [] ) {

        }

        public function save() {

        }

        public function delete() {

        }

        public function id() {
            return $this->get( $this->pk, 0 );
        }

    }