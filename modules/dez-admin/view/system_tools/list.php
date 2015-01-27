<ul>
    <? foreach( [
        [ 'requestEmulate', 'Эмуляция запросов' ],
        [ 'ORM_TableGenerator', 'Генератор таблиц ORM' ],
    ] as $link ): ?>
        <li><a href="<?= adminUrl( 'systemTools:index', [ 'name' => $link[0] ] ) ?>"><?= $link[1] ?></a></li>
    <? endforeach; ?>
</ul>