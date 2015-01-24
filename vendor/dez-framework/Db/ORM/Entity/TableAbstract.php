<?php

    namespace Dez\ORM\Entity;

    use Dez\ORM\Entity,
        Dez\ORM\Connection,
        Dez\ORM\Query,
        Dez\ORM\Common,
        Dez\ORM\Exception\Error as ORMException,
        Dez\ORM\Collection\RowsCollection;

    abstract class TableAbstract {

        static private
            $instances      = [];

        private
            $connection     = null,
            $builder        = null,
            $columns        = array(),
            $stmt           = null,
            $pagi           = null;

        protected function __construct( Connection\DBO $connection ) {
            if( ! isset( static::$tableName ) || ! static::$tableName ) {
                throw new ORMException( 'Not defined table name for: '. get_called_class() );
            }

            $this->connection = $connection;
            $this->setQueryBuilder( new Query\Builder( $connection ) );

            $this->columns      = $this->connection->getSchema()->getColumns( $this->getTableName() );
        }

        /**
         * @return \Dez\ORM\Connection\DBO $db
         */

        public function getConnection() {
            return $this->connection;
        }

        /**
         * @return \Dez\ORM\Connection\Stmt $stmt
         */

        public function getStmt() {
            return $this->stmt;
        }

        public function setStmt( Connection\Stmt $stmt ) {
            $this->stmt = $stmt;
            return $this;
        }

        /**
         * @return \Dez\ORM\Entity\Row[] $rows
        */

        protected function getCollection() {
            $items          = [];
            while( $row = $this->getStmt()->loadArray() )
                $items[]    = $this->rowInstance( $row );
            $collection     = new RowsCollection( $items );
            $collection->setTable( $this );
            $collection->setType( $this->rowClass() );
            return $collection;
        }

        /**
         * @return \Dez\ORM\Common\Pagi $pagi
        */

        public function getPagi() {
            return $this->pagi;
        }

        protected function setPagi( Common\Pagi $pagi ) {
            $this->pagi = $pagi;
        }

        /**
         * @return \Dez\ORM\Query\Builder $builder
         */

        public function getQueryBuilder() {
            return $this->builder;
        }

        public function setQueryBuilder( Query\Builder $builder ) {
            $this->builder = $builder;
        }

        public function getTableName() {
            return static::$tableName;
        }

        /**
         * @return static
         */

        static public function instance() {
            $hash = strtoupper( get_called_class() );
            if( ! isset( self::$instances[ $hash ] ) ) {
                self::$instances[ $hash ] = new static( );
            }
            return self::$instances[ $hash ];
        }

        public function rowClass() {
            return isset( static::$rowClass ) && class_exists( static::$rowClass )
                ? static::$rowClass
                : __NAMESPACE__ .'\Row';
        }

        /**
         * @param   array                       $row
         * @return  \Dez\ORM\Entity\Row         $row
         */

        public function rowInstance( array $row = [] ) {
            $rowClassName = $this->rowClass();
            return new $rowClassName( $row, $this );
        }

    }