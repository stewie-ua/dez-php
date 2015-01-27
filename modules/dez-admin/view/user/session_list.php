<?php

    /**
     * @var \Dez\ORM\Entity\Row[] $sessions
    */

    use Dez\Utils\HTML,
        Dez\Core\UrlBuilder as Url;
?>
<h1>Сессии</h1>
<table class="main">
    <thead>
    <tr>
        <td>Пользователь</td>
        <td>IP</td>
        <td>User-Agent</td>
        <td>Истекает</td>
        <td>Последнее обновление</td>
        <td></td>
    </tr>
    </thead>
    <? foreach( $sessions as $session ): ?>
    <tr>
        <td>
            <b>
                <?= HTML::a( adminUrl( 'users:profile', [ 'id' => $session->getUserId() ] ), $session->getUserId() ) ?>
            </b>
        </td>
        <td>
            <b>
                <?= long2ip( $session->getUserIp() ) ?>
            </b>
        </td>
        <td>
            <small>
                <?= $session->getUserAgent() ?>
            </small>
        </td>
        <td>
            <i>
                <?= $session->getExpiredDate() ?>
            </i>
        </td>
        <td>
            <i>
                <?= $session->getLastDate() ?>
            </i>
        </td>
        <td>
            <?=HTML::a(
                adminUrl( 'users:closeSession', [ 'id' => $session->id() ] ),
                'Закрыть сессию',
                [ 'class' => 'a-button' ]
            )?>
        </td>
    </tr>
    <? endforeach; ?>
</table>