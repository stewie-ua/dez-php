<?php

    namespace Dez\Auth\Acl;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,

        DB\Acl\Permission as PermissionDB;

    class Permission extends Object {

        use SingletonTrait;

        protected
            $row    = null;

        protected function init( $alias = null ) {
            $this->row = PermissionDB::instance()->filterBySystemKey( $alias )->findOne();
        }

        protected function getId() {
            return $this->row->id();
        }

    }