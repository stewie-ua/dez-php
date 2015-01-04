<?php

    namespace DB;

    use \Dez\ORM\Entity\Table   as ORMTable,
        \Dez\ORM\Entity\Row     as ORMRow;

    /**
     * @method ORMTable orderById( string $orderMode )
    */

    class User extends ORMTable {
        static protected
            $tableName  = 'system_auth',
            $rowClass   = '\DB\RowUser';
    }

    class RowUser extends ORMRow {

        public function registerDate() {
            return ( new \DateTime( $this->getAddedAt() ) )->format( 'd.m.Y H:i:s' );
        }

    }