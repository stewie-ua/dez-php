<?php

    namespace Dez\ORM\Entity;

    use Dez,
        Dez\ORM\Common,
        Dez\ORM\Query,
        Dez\ORM\Association\Association,
        Dez\ORM\Exception\Error as ORMException;

    class Table extends TableAbstract {

        static private
            $filterTypes = [ 'filterBy', 'groupBy', 'orderBy', 'limit', 'use' ];

        public function __construct() {
            parent::__construct( Dez\ORM::connect() );
        }

        /**
         * @param   string          $name
         * @param   array           $args
         * @throws  ORMException
         * @return  $this
        */

        public function __call( $name, array $args = [] ) {

            $pattern = '/^('. join( '|', self::$filterTypes ) .')/us';

            $this->getQueryBuilder()->select()->table( $this->getTableName() );

            if( preg_match( $pattern, $name ) ) {

                $target         = preg_split( $pattern, $name, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );

                switch( $target[0] ) {

                    case 'filterBy': {
                        if( isset( $target[1] ) && isset( $args[0] ) ) {
                            $columnName = Common\Utils::php2sql( $target[1] );
                            $this->getQueryBuilder()->where( array( $columnName, $args[0] ) );
                        } else {
                            throw new ORMException( 'Wrong filterBy request' );
                        }
                        break;
                    }

                    case 'groupBy': {
                        if( isset( $target[1] ) ) {
                            $columnName = Common\Utils::php2sql( $target[1] );
                            $this->getQueryBuilder()->group( $columnName );
                        } else if ( $args[0] ) {
                            $columnName = Common\Utils::php2sql( $args[0] );
                            $this->getQueryBuilder()->groupAlias( $columnName );
                        } else {
                            throw new ORMException( 'Wrong groupBy request' );
                        }
                        break;
                    }

                    case 'orderBy': {
                        if( isset( $target[1] ) ) {
                            $columnName = Common\Utils::php2sql( $target[1] );
                            $this->getQueryBuilder()->order( array( $columnName, $args[0] ) );
                        } else {
                            throw new ORMException( 'Wrong orderBy request' );
                        }
                        break;
                    }

                    case 'use': {
                        if( isset( $target[1] ) ) {
                            return Association::instance( $name, $this );
                        } else {
                            throw new ORMException( 'Wrong use request' );
                        }
                        break;
                    }

                    case 'limit': {
                        $this->getQueryBuilder()->limit( $args[0], $args[1] );
                        break;
                    }

                }

            } else {
                throw new ORMException( 'Calling undefined method '. get_called_class() .'::'. $name .'()' );
            }

            return $this;
        }

        public function pagi( $page = 0, $length = 0 ) {
            $qbCloned   = clone( $this->getQueryBuilder() );
            $qbCloned->select( [ $qbCloned->func( 'count', $this->pk(), [ 'distinct' ] ) ], false )->groupClear()->orderClear();

            $stmt = $this->getConnection()->query( $qbCloned->query() );

            $this->setPagi( new Common\Pagi( $page, $length, (int) $stmt->loadColumn() ) );
            $this->getQueryBuilder()->limit( $this->getPagi()->getOffset(), $this->getPagi()->getLength() );

            return $this;
        }

        public function pk() {
            return $this->getConnection()->getSchema()->getTablePK( $this->getTableName() );
        }

        /**
         * @return \Dez\ORM\Collection\RowsCollection $collection
         * @throws ORMException
         */

        public function find() {
            $stmt = $this->getConnection()->query( $this->getQueryBuilder()->query() );
            if( ! $stmt ) {
                throw new ORMException( 'Bad SQL query' );
            } else {
                $this->setStmt( $stmt );
            }
            return $this->getCollection();
        }

        /**
         * @return \Dez\ORM\Entity\Row $row
         * @throws ORMException
         */

        public function findOne() {
            $stmt = $this->getConnection()->query( $this->getQueryBuilder()->query() );
            if( ! $stmt ) {
                throw new ORMException( 'Bad SQL query' );
            } else {
                $this->setStmt( $stmt );
            }
            $row = $this->getStmt()->loadArray();
            return static::rowInstance( $row, $this );
        }

        /**
         * @return \Dez\ORM\Entity\Row[] $rows
         */

        static public function findAll() {
            $instance   = static::instance();
            $instance->getQueryBuilder()->select()->table( $instance->getTableName() );
            $instance->setStmt( $instance->getConnection()->query( $instance->getQueryBuilder()->query() ) );
            return $instance->getCollection();
        }

        /**
         * @param int $pk
         * @return \Dez\ORM\Entity\Row $row
         */

        static public function findPk( $pk = 0 ) {
            $instance   = static::instance();
            $query = $instance->getQueryBuilder()
                ->select()
                ->table( $instance->getTableName() )
                ->where( array( $instance->pk(), $pk ) )
                ->limit( 1 )
                ->query();
            $row = $instance->getConnection()->query( $query )->loadArray() ?: [];
            return static::row( $row );
        }

        /**
         * @param array     $data
         * @param boolean   $insertIgnore
         * @return \Dez\ORM\Entity\Row $row
         */

        static public function insert( array $data = array(), $insertIgnore = false ) {
            $row = static::row();
            $row->bind( $data )->save( $insertIgnore );
            return $row;
        }

        static public function row( $row = [] ) {
            return static::instance()->rowInstance( $row );
        }

    }