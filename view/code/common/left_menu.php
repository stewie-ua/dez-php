<?php use \Dez\Utils\NumConv; ?>
<ul>
    <li><a href="<?=url( 'code-share/add' )?>">Добавить</a></li>
    <li><a href="<?=url( 'code-share' )?>">К списку</a></li>
</ul>

<div class="code-latest">
    <h3>Посление добавления</h3>
    <ul>
        <?foreach( $latest as $item ):?>
            <li>
                <div>
                    <span class="item-lang"><?=$item->getLanguage()?></span>
                    <a class="code-title" href="<?=url( 'k/'. NumConv::instance()->encode( $item->id() ) )?>">
                        <?=$item->getTitle()?>
                    </a>
                </div>
                <span class="code-author">
                    Добавил:
                    <?if( $item->getAuthorId() ):?>
                        <a href="<?=url( 'users/'. $item->getAuthorId() )?>">#<?=$item->getAuthorId()?></a>
                    <?else:?>
                        <i>Гость</i>
                    <?endif;?>
                </span>
                <span class="code-added">
                    <?=$item->getAddedAt()?>
                </span>
            </li>
        <?endforeach;?>
    </ul>
</div>