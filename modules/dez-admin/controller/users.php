<?php

    use Dez\Controller\Controller,
        Dez\Core,
        Dez\Web\Layout,
        Dez\Common\Validator,
        Dez\Error\Error as ErrorMessage,
        Dez\Utils\NumConv,
        Dez\Utils\Crypt,

        Dez\ORM\Collection,
        Dez\ORM\Common\DataFormat;

    class UsersController extends Controller {

        public function beforeExecute() {
            Layout::instance()->set( 'left', $this->render(
                'user/inner/left', []
            ) );
        }

        public function indexAction() {
            $users = \DB\User::instance()->orderById( 'DESC' )->pagi( $this->request->get( 'page', 1 ), 10 )->find();
            return $this->render( 'user/list', [
                'users'     => $users
            ] );
        }

        public function profileAction() {
            $userId = $this->request->get( 'id', 0 );
            if( $userId > 0 ) {
                $user = \DB\User::instance()->findPk( $userId );
                if( $user->id() > 0 ) {
                    return $this->render( 'user/item', [
                        'user'          => $user,
                        'roles'         => DataFormat::getArrayValue( $this->model( 'acl' )->getRoles() )
                    ] );
                }
                ErrorMessage::critical( 'UserID: '. $userId .' not found' );
            }
            ErrorMessage::critical( 'User can not be found' );
        }

        public function profilePostAction() {

            $userId = $this->request->get( 'id', 0 );

            if( $userId > 0 ) {
                $user = \DB\User::instance()->findPk( $userId );
                if( $user->id() > 0 ) {
                    $usersModel = $this->model( 'users' );
                    $usersModel->save( $user );
                } else {
                    ErrorMessage::critical( 'Bad request [User ID wrong]' );
                }
            } else {
                ErrorMessage::critical( 'Bad request [User ID wrong]' );
            }

            $this->redirect( url() );
        }

        public function rolesAction() {
            return $this->render( 'user/roles', [
                'roles' => $this->model( 'acl' )->getRoles(),
            ] );
        }

        public function roleCreateAction() {
            return $this->render( 'user/role_form', [
                'roleItem'          => \DB\Acl\Role::row(),
                'permissionsGroups' => $this->model( 'acl' )->getPermissionGroups(),
                'permissions'       => $this->model( 'acl' )->getPermissions(),
            ] );
        }

        public function roleEditAction() {
            $roleId = $this->request->get( 'id', -1 );
            return $this->render( 'user/role_form', [
                'roleItem'          => \DB\Acl\Role::findPk( $roleId ),
                'permissionsGroups' => $this->model( 'acl' )->getPermissionGroups(),
                'permissions'       => $this->model( 'acl' )->getPermissions(),
            ] );
        }

        public function roleSavePostAction() {
            if( $this->request->isPost() ) {
                $role   = \DB\Acl\Role::findPk( $this->request->post( 'id', 0 ) );
                $role->setName( $this->request->post( 'name', 'Unnamed role' ) );
                $role->setLevel( $this->request->post( 'permissions', [] ) );
                $role->save();
                $this->redirect( adminUrl( 'users:roles', ['saved_row' => $role->id()] ) );
            }
            ErrorMessage::notify( 'Адрес не доступен' );
            $this->redirect( adminUrl( 'users:roles', ['error' => 'access_denied'] ) );
        }

        public function permissionsAction() {
            return $this->render( 'user/permissions', [
                'permissionsGroups' => $this->model( 'acl' )->getPermissionGroups(),
                'permissions'       => $this->model( 'acl' )->getPermissions()
            ] );
        }

        public function permissionItemAction() {
            $permissionItem = $this->model( 'acl' )->getPermissionById( $this->request->get( 'id', -1 ) );
            return $this->render( 'user/permission_form', [
                'permissionItem'    => $permissionItem,
                'permissionGroups'  => $this->model( 'acl' )->getPermissionGroups()
            ] );
        }

        public function permissionCreateAction() {
            $permissionItem = \DB\Acl\Permission::row();
            return $this->render( 'user/permission_form', [
                'permissionItem'    => $permissionItem,
                'permissionGroups'  => $this->model( 'acl' )->getPermissionGroups()
            ] );
        }

        public function permissionItemPostAction() {
            if( $this->request->isPost() ) {
                $permissionItem = \DB\Acl\Permission::findPk( $this->request->post( 'id', -1 ) );
                $permissionItem->setName( $this->request->post( 'name', 'UNKNOWN NAME' ) );
                $permissionItem->setSystemKey( strtoupper( $this->request->post( 'system_key', 'UNKNOWN_KEY' ) ) );
                $permissionItem->setGroupId( $this->request->post( 'group_id', $this->request->get( 'groupId', 1 ) ) );
                $permissionItem->save();
                $this->redirect( adminUrl( 'users:permissions' ) );
            }
            ErrorMessage::notify( 'Адрес не доступен' );
            $this->redirect( adminUrl( 'users:permissions', ['error' => 'access_denied'] ) );
        }

        public function permissionDeleteAction () {
            \DB\Acl\Permission::findPk( $this->request->get( 'id', -1 ) )->delete();
            $this->redirect( adminUrl( 'users:permissions' ) );
        }

    }