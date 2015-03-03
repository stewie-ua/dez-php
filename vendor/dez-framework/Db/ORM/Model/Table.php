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

        static public function one( $id = 0 ) {
            return static::query()->findOne( $id );
        }

        static public function insert( array $data = [] ) {
            return static::instance()->bind( $data )->save();
        }

        public function save() {
            $query = QueryBuilder::instance( $this );
            return $this->exists() ? $query->update() : $query->insert();
        }

        public function delete() {

        }

        public function id() {
            return $this->get( $this->pk, 0 );
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