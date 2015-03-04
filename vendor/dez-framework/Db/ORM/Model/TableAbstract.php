<?php

    namespace Dez\ORM\Model;

    use Dez\ORM;
    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\Utils;
    use Dez\ORM\Connection\DBO as DbConnection;
    use Dez\ORM\Connection\Stmt;
    use Dez\ORM\Exception\Error as ORMException;
    use Dez\ORM\Collection\ModelCollection;

    abstract class TableAbstract extends Object {

        protected
            $connection     = null,
            $data           = [],
            $pk             = null,
            $id             = 0;

        /**
         * @throws ORMException
         */

        public function __construct() {
            if( ! $this->hasTable() ) {
                throw new ORMException( 'Not defined table name for: '. $this->getTableName() );
            }

            $this->setConnection( ORM::connect() );
            $this->pk   = $this->getConnection()->getSchema()->getTablePK( $this->getTableName() );
            $this->id   = $this->get( $this->pk, 0 );

            if( $this->id() > 0 ) {
                unset( $this->data[$this->pk] );
            }
        }

        /**
         * @return static
         * @throws ORMException
         */

        public function __call( $name, $args ) {

            $methodName     = substr( $name, 0, 3 );
            $columnName     = substr( $name, 3 );
            $columnValue    = isset( $args[0] ) ? $args[0] : null;

            switch( $methodName ) {
                case 'set': {
                    return $this->set( $this->getSQLName( $columnName ), $columnValue );
                    break;
                }
                case 'get': {
                    return $this->get( $this->getSQLName( $columnName ) );
                    break;
                }
                default: {
                    throw new ORMException( 'Call undefined method' );
                }
            }
        }

        /**
         * @return mixed
         * @param string $name
         */

        public function __get( $name = null ) {
            return $this->get( $name );
        }

        /**
         * @return static
         * @param string $name
         * @param string $value
         */

        public function __set( $name = null, $value = null ) {
            return $this->set( $name, $value );
        }

        /**
         * @return mixed
         * @param string $name
         * @param mixed $default
         */

        public function get( $name, $default = null ) {
            return isset( $this->data[$name] ) ? $this->data[$name] : $default;
        }

        /**
         * @return static
         */

        public function set( $name = null, $value = null ) {
            $this->data[$name] = $value;
            return $this;
        }

        /**
         * @return static
         */

        public function bind( array $data = [] ) {
            foreach( $data as $key => $value )
                $this->$key     = $value;
            return $this;
        }

        public function setId( $value = 0 ) {
            if( 0 > $this->id() ) $this->id = (int) $value;
        }

        /**
         * @return string $tableName
         */

        public function getTableName() {
            return ! $this->hasTable() ?: static::$table;
        }

        /**
         * @return static
         * @param DbConnection $connection
         */

        public function setConnection( DbConnection $connection ) {
            $this->connection   = $connection;
            return $this;
        }

        /**
         * @return DbConnection $connection
         */

        public function getConnection() {
            return $this->connection;
        }

        /**
         * @return ModelCollection $collection
         * @param Stmt $stmt
        */

        public function createCollection( Stmt $stmt ) {
            $collection = new ModelCollection();
            $collection->setType( $this->getClassName() );
            while( $model = $stmt->loadIntoObject( $this->getClassName() ) ) {
                $collection->add( $model );
            }
            return $collection;
        }

        /**
         * @return boolean
        */

        public function exists() {
            return ( $this->id() > 0 );
        }

        /**
         * @return boolean
        */

        protected function hasTable() {
            return property_exists( $this->getClassName(), 'table' );
        }

        /**
         * @return string $name
         */

        protected function getSQLName( $phpName = null ) {
            return ! $phpName ? null : Utils::php2sql( $phpName );
        }

        /**
         * @return int $id
        */

        abstract public function id();

    }