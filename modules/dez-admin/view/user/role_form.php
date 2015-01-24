<?php

    use Dez\Utils\HTML,
        Dez\Auth\ACL\Role;

    /**
     * @var $layout     \Dez\Web\Layout
     * @var $roleItem   \DB\Acl\RoleRow
     * @var $acl        \Dez\Auth\ACL\ACL
    */

    $layout->addTitle( 'Редактирование роли #'. $roleItem->id() );

?>
<script>
    app.DOM.ready(function($){ $('.role-permissions').tabs($); });
</script>
<form action="<?= adminUrl( 'users:roleSave' ) ?>" method="post">

    <table class="main">
        <thead>
        <tr>
            <td colspan="2">
                <b><?= $roleItem->getName() ?> #<?= $roleItem->id() ?></b>
            </td>
        </tr>
        </thead>

        <tr>
            <td>Название</td>
            <td>
                <?= HTML::onceTag( 'input', [
                    'type'  => 'text',
                    'name'  => 'name',
                    'value' => $roleItem->getName()
                ] ) ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">Права доступа</td>
        </tr>

        <tr>
            <td colspan="2">

                <div class="role-permissions">

                    <ul class="dez-tabs">
                        <? foreach( $permissionsGroups as $groupId => $groupName ): ?>
                            <li>
                                <a data-tab-id="<?= $groupId ?>" href=""><?= $groupName ?></a>
                            </li>
                        <? endforeach; ?>
                    </ul>

                    <div class="dez-tabs-box">
                        <? foreach( $permissionsGroups as $groupId => $groupName ): ?>
                            <div class="dez-tab-content" id="tab-id-<?= $groupId ?>">

                                <ul class="dez-checkbox-list">

                                    <? if( isset( $permissions[$groupId] ) ) foreach( $permissions[$groupId] as $permission ): ?>
                                        <li>
                                            <?= HTML::checkbox(
                                                'permissions[]',
                                                $permission->id(),
                                                Role::hasPermission( $permission->id(), $roleItem->getLevel() )
                                            ) ?>
                                            <?= $permission->getName() ?>
                                        </li>
                                    <? endforeach; ?>

                                </ul>

                            </div>
                        <? endforeach; ?>
                    </div>

                </div>

            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" value="Сохранить"/>
                <input name="id" type="hidden" value="<?= $roleItem->id() ?>"/>
            </td>
        </tr>

    </table>

</form>