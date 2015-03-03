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
                        $this->getNativeBuilder()->where( [ $columnName, $args[0] ] );
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
                    case 'limit': {
                        $this->getNativeBuilder()->limit( $args[0], $args[1] );
                        break;
                    }
                }

                return $this;

            } else {
                parent::__call( $name, $args );
            }

        }

        /**
         * @param $model Table
        */

        protected function init( $model ) {
            $this->setModel( $model );
            $this->setNativeBuilder( new Builder( $this->getModel()->getConnection() ) );
            $this->getNativeBuilder()->table( $this->getModel()->getTableName() );
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
            $stmt   = $this->getModel()->getConnection()->query( $this->getNativeBuilder()->select()->query() );
            return $this->getModel()->createCollection( $stmt );
        }

        /**
         * @return Table $model
         */

        public function findOne( $id = 0 ) {
            $builder    = $this->getNativeBuilder();
            $builder->where( [ $this->getModel()->pk(), $id ] );
            $stmt       = $this->getModel()->getConnection()->query( $builder->select()->query() );
            return $stmt->loadIntoObject( $this->getModel()->getClassName() );
        }

        /**
         * @return int|boolean
        */

        public function insert() {
            $query  = $this->getNativeBuilder()->bind( $this->getModel()->toArray() )->insert()->query();
            dump($query);
            $stmt   = $this->getModel()->getConnection()->query(  );
        }

        /**
         * @return int|boolean
         */

        public function update() {

        }

        /**
         * @return string $name
        */

        protected function getSQLName( $phpName = null ) {
            return ! $phpName ? null : Utils::php2sql( $phpName );
        }

    }