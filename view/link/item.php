<?php use \Dez\Utils\NumConv; ?>
<h1>URL ID: <?=$item->id()?></h1>
<div class="content-block">
    <div class="short-url">
        <?=url( 'go/'. NumConv::instance()->encode( $item->id() ), [], true )?>
    </div>
</div>