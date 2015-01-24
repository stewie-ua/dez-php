<?php

    namespace Dez\ORM\Entity;

    use Dez\ORM\Connection,
        Dez\ORM\Query,
        Dez\ORM\Common,

        Dez\ORM\Exception\Error as ORMException;

    class Row extends RowIterator {

        protected
            $connection     = null,
            $table          = null,
            $row            = [],
            $pk             = 'id',
            $id             = 0,
            $builder        = null;

        public function __construct( array & $row = [], Table $table ) {
            $this->table        = $table;
            $this->row          = & $row;
            $this->pk           = $this->table->pk();
            $this->id           = $this->get( $this->pk, 0 );
            $this->connection   = $this->table->getConnection();
            $this->builder      = $this->table->getQueryBuilder();
            if( isset( $this->row[$this->pk] ) ) unset( $this->row[$this->pk] );
        }

        public function __destruct() {
            $this->onDestroy();
        }

        public function __call( $name, $args ) {
            switch( substr( $name, 0, 3 ) ) {
                case 'set': {
                    return $this->set( Common\Utils::php2sql( substr( $name, 3 ) ), $args[0] );
                    break;
                }
                case 'get': {
                    return $this->get( Common\Utils::php2sql( substr( $name, 3 ) ) );
                    break;
                }
                default: {
                    throw new ORMException( 'Call undefined method' );
                }
            }
        }

        public function __get( $key ) {
            return $this->get( $key );
        }

        public function __set( $key, $data = null ) {
            $this->set( $key, $data );
        }

        public function get( $key = null, $default = null ) {
            return isset( $this->row[$key] ) ? $this->row[$key] : $default;
        }

        public function set( $key = null, $value = null ) {
            $this->row[$key] = $value;
            return $this;
        }

        public function bind( array $data = array() ) {
            foreach( $data as $key => $value ) {
                $this->set( $key, $value );
            }
            return $this;
        }

        public function column( $key = null, $default = null ) {
            return $this->get( $key, $default );
        }

        public function c( $key = null, $default = null ) {
            return $this->get( $key, $default );
        }

        public function id() {
            return $this->id;
        }

        public function pk() {
            return $this->pk;
        }

        public function toJSON() {
            return json_encode( (object) $this->row );
        }

        public function toArray() {
            return $this->row;
        }

        public function toObject() {
            return (object) $this->row;
        }

        public function save( $insertIgnore = false ) {
            $saveResult = null;
            if( $this->id() > 0 ) {
                $this->beforeUpdate();
                $saveResult =  $this->connection->execute(
                    $this->builder
                        ->update()
                        ->table( $this->table->getTableName() )
                        ->bind( $this->toArray() )
                        ->where( array( $this->pk(), $this->id() ) )
                        ->limit( 1 )
                        ->query()
                )->affectedRows();
                $this->afterUpdate();
            } else {
                $this->beforeSave();
                $this->builder->insert();
                if( $insertIgnore === true ){
                    $this->builder->ignore();
                }
                $query = $this->builder->table( $this->table->getTableName() )->bind( $this->toArray() )->query();
                $saveResult = $this->id = $this->connection->execute( $query  )->lastInsertId();
                $this->afterSave();
            }
            return $saveResult;
        }

        public function delete() {
            $this->beforeDelete(); $deleteResult = 0;
            if ( $this->id() > 0 ) {
                $deleteResult = $this->connection->execute(
                    $this->builder
                        ->delete()
                        ->table( $this->table->getTableName() )
                        ->where( array( $this->pk(), $this->id() ) )
                        ->limit( 1 )
                        ->query()
                )->affectedRows();
                $this->afterDelete();
            }
            return $deleteResult;
        }

        protected function beforeSave() {}

        protected function beforeUpdate() {}

        protected function beforeDelete() {}

        protected function afterSave() {}

        protected function afterUpdate() {}

        protected function afterDelete() {}

        protected function onDestroy() {}

    }