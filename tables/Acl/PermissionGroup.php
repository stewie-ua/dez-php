<?php

    namespace DB\Acl;

    use Dez\ORM\Entity\Table    as ORMTable,
        Dez\ORM\Entity\Row      as ORMRow;

    class PermissionGroup extends ORMTable {
        static protected
            $tableName  = 'acl_groups',
            $rowClass   = '\DB\Acl\PermissionGroupRow';
    }

    class PermissionGroupRow extends ORMRow {

    }