<?php

    namespace DB;

    use \Dez\ORM\Entity;

    class User extends Entity\Table {
        static protected
            $tableName  = 'system_auth',
            $rowClass   = '\DB\RowUser';
    }

    class RowUser extends Entity\Row {

        public function registerDate() {
            return ( new \DateTime( $this->getAddedAt() ) )->format( 'd.m.Y H:i:s' );
        }

    }