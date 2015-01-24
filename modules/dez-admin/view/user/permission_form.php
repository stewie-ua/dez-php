<?php

    use Dez\Utils\HTML;

    /**
     * @var $permissionItem \DB\Acl\PermissionRow
     */
?>
<form action="<?= adminUrl( 'users:permissionItem' ) ?>" method="post">

    <table class="main">
        <thead>
        <tr>
            <td colspan="2">
                <b>#<?= $permissionItem->id() ?></b>
            </td>
        </tr>
        </thead>
        <tr>
            <td>Название</td>
            <td>
                <?= HTML::onceTag( 'input', [
                    'type'  => 'text',
                    'name'  => 'name',
                    'value' => $permissionItem->getName()
                ] ) ?>
            </td>
        </tr>
        <tr>
            <td>Системное название</td>
            <td>
                <?= HTML::onceTag( 'input', [
                    'type'  => 'text',
                    'name'  => 'system_key',
                    'value' => $permissionItem->getSystemKey()
                ] ) ?>
            </td>
        </tr>
        <tr>
            <td>Граппа</td>
            <td>
                <?= HTML::select( $permissionGroups, 'group_id', $permissionItem->getGroupId() ) ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" value="Сохранить"/>
            </td>
        </tr>

    </table>

    <input name="id" type="hidden" value="<?= $permissionItem->id() ?>"/>

</form>