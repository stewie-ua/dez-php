<?php

    namespace Dez\Auth\ACL;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,

        DB\User         as UserDB,
        DB\Acl\Role     as ACLRoleDB;

    class ACL extends Object {

        use SingletonTrait;

        protected
            $role   = null,
            $user   = null;

        protected function init( $roleId = 0 ) {
            $this->role     = AclRoleDB::findPk( $roleId );
        }

        public function getRole() {
            return $this->role;
        }

        public function roleHas( $permissionAlias ) {
            $permission = Permission::instance( $permissionAlias );
            return Role::hasPermission( $permission->id, $this->role->getLevel() );
        }

        static public function hasUserPermission( $userId, $permissionAlias ) {
            return static::instance( UserDB::findPk( $userId )->getAclRoleId() )->roleHas( $permissionAlias );
        }

    }