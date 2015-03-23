<?php

    namespace Dez\ORM\Model;

    use Dez\ORM;
    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;
    use Dez\ORM\Query\Builder;
    use Dez\ORM\Collection\ModelCollection;
    use Dez\ORM\Common\Utils;

    class QueryBuilder extends Object {

        use SingletonTrait;

        protected
            $connection = null,
            $builder    = null,
            $model      = null,
            $methods    = [ 'where', 'group', 'order', 'limit' ];

        public function __call( $name, $args ) {

            $pattern = '/^('. join( '|', $this->methods ) .')/us';

            if( preg_match( $pattern, $name ) ) {

                $target         = preg_split( $pattern, $name, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );

                $methodName     = $target[0];
                $columnName     = isset( $target[1] ) ? $this->getSQLName( $target[1] ) : null;

                switch( $methodName ) {
                    case 'where': {
                        $this->where( $columnName, $args[0], isset( $args[1] ) ? $args[1] : '=' );
                        break;
                    }
                    case 'group': {
                        $this->getNativeBuilder()->group( $columnName );
                        break;
                    }
                    case 'order': {
                        $this->getNativeBuilder()->order( [ $columnName, $args[0] ] );
                        break;
                    }
                }

                return $this;

            } else {
                parent::__call( $name, $args );
            }

        }

        /**
         * @return static
         */

        public function where( $columnName = null, $columnValue = null, $cmpType = '=' ) {
            $this->getNativeBuilder()->where( [ $columnName, $columnValue, $cmpType ] );
            return $this;
        }

        /**
         * @return static
         */

        public function limit() {
            $this->getNativeBuilder()->limit( $args[0], $args[1] );
            return $this;
        }

        /**
         * @param $model Table
        */

        protected function init( $model ) {
            $this->setModel( $model );
            $this->setNativeBuilder( new Builder( $model->getConnection() ) );
            $this->getNativeBuilder()->table( $model->getTableName() );
        }

        /**
         * @return static
         */

        public function setModel( Table $model ) {
            $this->model    = $model;
            return $this;
        }

        /**
         * @return Table $model
        */

        public function getModel() {
            return $this->model;
        }

        /**
         * @return static
         */

        public function setNativeBuilder( Builder $builder ) {
            $this->builder   = $builder;
            return $this;
        }

        /**
         * @return Builder $builder
         */

        public function getNativeBuilder() {
            return $this->builder;
        }

        /**
         * @return ModelCollection $collection
         */

        public function find() {
            $query  = $this->getNativeBuilder()->select()->query();
            $stmt   = $this->getModel()->getConnection()->query( $query );
            return $this->getModel()->createCollection( $stmt );
        }

        /**
         * @return Table $model
         */

        public function first() {
            $query  = $this->getNativeBuilder()->select()->limit( 1 )->query();
            $stmt   = $this->getModel()->getConnection()->query( $query );
            return $this->getModel()->bind( $stmt->loadArray() ?: [] );
        }

        /**
         * @return Table $model
         */

        public function findOne( $id = 0 ) {
            $builder    = $this->getNativeBuilder();
            $builder->where( [ $this->getModel()->pk(), $id ] );
            $stmt       = $this->getModel()->getConnection()->query( $builder->select()->query() );
            return $this->getModel()->bind( $stmt->loadArray() ?: [] );
        }

        /**
         * @return int|boolean
        */

        public function insert() {
            $query  = $this->getNativeBuilder()->bind( $this->getModel()->toArray() )->insert()->query();
            return $this->getModel()->getConnection()->execute( $query )->lastInsertId();
        }

        /**
         * @return int|boolean
         */

        public function update() {
            $model      = $this->getModel();
            $builder    = $this->getNativeBuilder()->bind( $model->toArray() );
            $builder->update()->where( [ $model->pk(), $model->id() ] )->limit( 1 );
            return $model->getConnection()->execute( $builder->query() )->affectedRows();
        }

        /**
         * @return int|boolean
         */

        public function delete() {
            $model      = $this->getModel();
            $this->getNativeBuilder()->delete()->where( [ $model->pk(), $model->id() ] )->limit( 1 );
            return $model->getConnection()->execute( $this->getNativeBuilder()->query() )->affectedRows();
        }

        /**
         * @return string $name
        */

        protected function getSQLName( $phpName = null ) {
            return ! $phpName ? null : Utils::php2sql( $phpName );
        }

    }