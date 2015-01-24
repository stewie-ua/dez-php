<ul>
    <? foreach( [
        [ 'requestEmulate', 'Эмуляция запросов' ],
    ] as $link ): ?>
        <li><a href="<?= adminUrl( 'systemTools:index', [ 'name' => $link[0] ] ) ?>"><?= $link[1] ?></a></li>
    <? endforeach; ?>
</ul>