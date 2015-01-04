<?php

    namespace DB;

    use \Dez\ORM\Entity\Table   as ORMTable,
        \Dez\ORM\Entity\Row     as ORMRow;

    /**
     * @method ORMTable orderById( string $orderMode )
     * @method ORMTable orderByAlias( string $orderMode )
     * @method ORMTable orderByTitle( string $orderMode )
     */

    class Access extends ORMTable {
        static protected
            $tableName  = 'system_auth_access',
            $rowClass   = '\DB\AccessRow';
    }

    /**
     * @method int getId()
     * @method string getAlias()
     * @method string getTitle()
     */

    class AccessRow extends ORMRow {}