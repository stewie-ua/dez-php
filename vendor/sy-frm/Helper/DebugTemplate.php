<?php
    use Sy\Utils\HTML;
?>
<style>

    .debugger-wrap {
        position: relative;
        font-size: 10px;
    }

    .debugger {
        position: fixed;
        bottom: 0px;
        width: 100%;
        height: 30px;
        background: #AAAFBD;
        z-index: 1000;
        box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.44);
        border-top: 1px solid rgb(150, 150, 150);
        line-height: 30px;
    }

    .debugger li {
        display: inline-block;
        text-shadow: 1px 1px 1px #fff;
    }

    .debugger .system-info {
        float: right;
        margin-right: 10px;
    }

    .debugger .debugger-name {
        margin-left: 10px;
        margin-right: 10px;
        background: #C43F00;
        color: #fff;
        padding: 2px 10px;
        border-radius: 6px;
        border: 1px solid rgb(105, 34, 0);
        font-weight: bold;
        text-shadow: 1px 1px 0px #000;
    }

    .debugger-wrap pre {
        font-family: "courier new";
        font-size: 12px;
        border: 1px solid rgb(172, 172, 172);
        margin-bottom: 10px;
        padding: 5px 5px;
        background: #fff;
        white-space: pre-wrap;
    }

    .debug-links {
        float: left;
    }

    .debug-links li {
        margin-right: 5px;
    }

    .debug-links li a {
        text-decoration: none;
        color: #000;
        border: 1px solid white;
        padding: 2px 10px;
        background: #CACACA;
        border-radius: 6px;
    }

    .debug-links li a:hover {
        background: #797979;
        border: 1px solid rgb(88, 88, 88);
        color: #fff;
        text-shadow: 1px 1px 0px #000;
    }

    .debug-content{
        position: fixed;
        bottom: 30px;
        background: rgba(226, 222, 222, 0.93);
        width: 100%;
        min-height: 350px;
        border-top: 1px solid rgb(201, 201, 201);
        z-index: 999;
        box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.44);
    }

    .debug-content a.close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #FF2900;
        text-decoration: none;
        font-weight: bold;
        color: #FFF;
        padding: 5px 15px;
        text-shadow: 1px 1px 0px #000;
        border-radius: 6px;
    }

    .debug-content-box {
        margin: 20px;
    }

</style>
<?php
    $sqlJson = [];
    foreach( $data['sql'] as $sql ) {
        $sqlJson[] = HTML::tag( 'pre', $sql, [] );
    }
?>
<script>

    window.addEventListener("DOMContentLoaded", function() {
        document.querySelector('#debug-close-button').addEventListener('click', function() {
            hideElement( 'debug-content' );
        }, false);

        DOM.get('.debug-links').click(function(e) {
            if (e.target.tagName.toLowerCase() == 'a') {
                console.log(e.target);
            }
        });
        console.dir(DOM.get('.debug-links'))
    }, false);






    var getById = function( id ){ return document.getElementById( id );},
        hideElement = function( id ) { document.getElementById( id ).style.display = 'none'; },
        showElement = function( id ) { document.getElementById( id ).style.display = 'block'; };
    var debugSql        = <?= json_encode( $sqlJson ) ?>,
        debugSession    = <?= json_encode( var_export( $_SESSION, true ) ) ?>;
</script>
<div class="debugger-wrap">
    <div class="debug-content" id="debug-content">
        <div class="debug-content-box"><b>NULL</b></div>
        <a id="debug-close-button" class="close" href="javascript: return false;">Close [X]</a>
    </div>
    <div class="debugger">

        <ul class="debug-links">
            <li>
                <span class="debugger-name">
                    <?php print $data['debugger_name']; ?>
                </span>
            </li>
            <li><a href="javascript:void(0)">SQL <b>(<?= count( $data['sql'] ) ?>)</b></a></li>
            <li><a href="javascript:void(0)">Request</a></li>
            <li><a href="javascript:void(0)">Session</a></li>
            <li><a href="javascript:void(0)">Auth</a></li>
        </ul>

        <ul class="system-info">
            <li>
                <b><?php print \Sy::poweredBy(); ?></b> |
            </li>
            <li>
                ENV: <b><?php print \Sy::env(); ?></b> |
            </li>
            <li>
                Use memory: <b><?php print \Sy::getMemoryUse(); ?>kB</b> |
            </li>
            <li>
                Execute time: <b><?php print \Sy::getTimeDiff(); ?>s</b>
            </li>
        </ul>
        <div style="clear: both;"></div>
    </div>
</div>