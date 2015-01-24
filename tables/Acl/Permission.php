<?php

    namespace DB\Acl;

    use Dez\ORM\Entity\Table    as ORMTable,
        Dez\ORM\Entity\Row      as ORMRow;

    class Permission extends ORMTable {
        static protected
            $tableName  = 'acl_permissions',
            $rowClass   = '\DB\Acl\PermissionRow';
    }

    class PermissionRow extends ORMRow {

    }