<?php

    namespace DB\Acl;

    use Dez\ORM\Entity\Table    as ORMTable,
        Dez\ORM\Entity\Row      as ORMRow,

        Dez\Auth\ACL;

    class Role extends ORMTable {
        static protected
            $tableName  = 'acl_roles',
            $rowClass   = '\DB\Acl\RoleRow';
    }

    class RoleRow extends ORMRow {

        protected function beforeSave() {
            if( ! is_string( $this->getLevel() ) )
                $this->setLevel( ACL\Role::permissionsToString( $this->getLevel() ) );
        }

        protected function beforeUpdate() {
            $this->beforeSave();
        }

    }