<?php

    use Dez\ORM\Common\DataFormat,

        Dez\Error\Error as ErrorMessage,
        Dez\Common\Validator;

    class AclModel extends Dez\Core\Model {

        public function getRoles() {
            return \DB\Acl\Role::findAll();
        }

        public function getPermissions() {
            return DataFormat::getArrayGroupedByColumn( \DB\Acl\Permission::findAll(), 'group_id' );
        }

        public function getPermissionById( $id ) {
            return \DB\Acl\Permission::findPk( $id );
        }

        public function getPermissionGroups() {
            return DataFormat::getArrayValue( \DB\Acl\PermissionGroup::findAll() );
        }

    }