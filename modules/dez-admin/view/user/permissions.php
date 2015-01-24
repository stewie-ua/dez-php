<?php

    /**
     * @var $permissions \DB\Acl\PermissionRow[]
    */

    use Dez\Utils\HTML;
?>
<h1>Права доступа ролей</h1>

<script>
    app.DOM.ready(function($){
        $('.permissions-tabs').tabs($);
    });
</script>

<div class="permissions-tabs">

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

                <table class="main">
                    <thead>
                    <tr>
                        <td>Название</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tr>
                        <td colspan="2" class="text-left">
                            <?= HTML::a( adminUrl( 'users:permissionCreate', [ 'groupId' => $groupId ] ), 'Добавить', [ 'class' => 'a-button' ] ) ?>
                        </td>
                    </tr>
                    <? if( isset( $permissions[$groupId] ) ) foreach( $permissions[$groupId] as $permission ): ?>
                        <tr>
                            <td>
                                <?= $permission->getName() ?> (<b><?= $permission->getSystemKey() ?></b>)
                            </td>
                            <td class="text-right">
                                <?= HTML::a( adminUrl( 'users:permissionItem', [ 'id' => $permission->id() ] ), 'Редактировать', [ 'class' => 'a-button' ] ) ?>
                                <?= HTML::a( adminUrl( 'users:permissionDelete', [ 'id' => $permission->id() ] ), 'Удалить', [ 'class' => 'a-button' ] ) ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </table>

            </div>
        <? endforeach; ?>
    </div>

</div>
