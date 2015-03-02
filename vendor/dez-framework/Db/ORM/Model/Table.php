<?php

    namespace Dez\ORM\Model;

    use Dez\ORM\Collection\ModelCollection;

    class Table extends TableAbstract {

        /**
         * @return QueryBuilder $builder
        */

        static public function query() {
            return QueryBuilder::instance( static::instance() );
        }

        /**
         * @return ModelCollection $collection
         */

        static public function all() {
            return static::query()->find();
        }

        static public function one( $id = 0 ) {
            return static::query()->findOne( $id );
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

        public function pk() {
            return $this->pk;
        }

    }