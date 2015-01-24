<?php

    use Dez\Utils\HTML;

    /**
     * @var $accessList \DB\AccessRow[]
    */
?>
<form action="<?= url() ?>" method="post">

    <table class="main">
        <thead>
        <tr>
            <td colspan="2">
                Пользователь <b>#<?= $user->id() ?></b>
            </td>
        </tr>
        </thead>
        <tr>
            <td>Логин</td>
            <td><?= $user->getLogin() ?></td>
        </tr>
        <tr>
            <td>E-mail</td>
            <td><?= $user->getEmail() ?></td>
        </tr>
        <tr>
            <td>Зарегестрирован</td>
            <td><?= $user->registerDate() ?></td>
        </tr>
        <tr>
            <td>Роль</td>
            <td>
                <?= HTML::select( $roles, 'user[acl_role_id]', $user->getAclRoleId() ) ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" value="Сохранить"/>
            </td>
        </tr>

    </table>

</form>