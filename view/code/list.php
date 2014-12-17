<?php use \Dez\Utils\NumConv; ?>
<h1>Посление добавленые</h1>
<div class="code-list">
    <ul>
        <?foreach( $items as $i => $item ):?>
        <li class="code-item <?=( $i % 2 == 0 ? 'odd' : 'even' )?>">
            <a href="<?=url( 'k/'. NumConv::instance()->encode( $item->id() ) )?>" class="lang-<?=$item->getLanguage()?>">
                <span class="item-title"><?=$item->getTitle()?></span>
                <span class="item-lang"><?=$item->getLanguage()?></span>
                <span class="item-added"><?=$item->getAddedAt()?></span>
            </a>
        </li>
        <?endforeach;?>
    </ul>
    <?=\Helper\Common::pagi( $items->getPagi() )?>
</div>
