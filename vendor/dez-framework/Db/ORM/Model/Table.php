<?php

    namespace Dez\ORM\Model;

    use Dez\ORM\Collection\ModelCollection;
    use Dez\ORM\Query\Builder as BaseQueryBuilder;

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

        /**
         * @return static
        */

        static public function one( $id = 0 ) {
            return static::query()->findOne( $id );
        }

        /**
         * @return static
         */

        static public function insert( array $data = [] ) {
            $model  = static::instance();
            $model->bind( $data )->save();
            return $model;
        }

        public function save() {
            $query = QueryBuilder::instance( $this );
            return $this->exists() ? $query->update() : $this->id = $query->insert();
        }

        public function delete() {

        }

        public function id() {
            return $this->id;
        }

        public function pk() {
            return $this->pk;
        }

        public function toArray() {
            return (array) $this->data;
        }

        public function toObject() {
            return (object) $this->data;
        }

        public function toJSON() {
            return json_encode( $this->toArray() );
        }

    }