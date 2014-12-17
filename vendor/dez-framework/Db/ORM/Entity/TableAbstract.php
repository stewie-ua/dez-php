<?php

    namespace Dez\ORM\Entity;

    use Dez\ORM\Entity,
        Dez\ORM\Connection,
        Dez\ORM\Query,
        Dez\ORM\Common,
        Dez\ORM\Exception\Error as ORMException;

    abstract class TableAbstract extends TableIterator {

        static private
            $instances = array();

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

        public function getConnection() {
            return $this->connection;
        }

        public function getStmt() {
            return $this->stmt;
        }

        public function setStmt( Connection\Stmt $stmt ) {
            $this->stmt = $stmt;
            return $this;
        }

        public function getPagi() {
            return $this->pagi;
        }

        protected function setPagi( Common\Pagi $pagi ) {
            $this->pagi = $pagi;
        }

        public function getQueryBuilder() {
            return $this->builder;
        }

        public function setQueryBuilder( Query\Builder $builder ) {
            $this->builder = $builder;
        }

        public function getTableName() {
            return static::$tableName;
        }

        static public function instance() {
            $hash = strtoupper( get_called_class() );
            if( ! isset( self::$instances[ $hash ] ) ) {
                self::$instances[ $hash ] = new static( );
            }
            return self::$instances[ $hash ];
        }

        static protected function rowInstance( array $row = [], Table $table = null ) {
            $rowClassName = isset( static::$rowClass ) && class_exists( static::$rowClass )
                ? static::$rowClass
                : __NAMESPACE__ .'\Row';
            return new $rowClassName( $row, $table );
        }

    }