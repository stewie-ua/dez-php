<?php

    namespace DB;

    use \Dez\ORM\Entity\Table   as ORMTable,
        \Dez\ORM\Entity\Row     as ORMRow;

    /**
     * @method ORMTable orderById( string $orderMode )
     */

    class UserSession extends ORMTable {
        static protected
            $tableName      = 'system_sessions',
            $rowClass       = '\DB\RowUserSession';
    }

    class RowUserSession extends ORMRow {



    }