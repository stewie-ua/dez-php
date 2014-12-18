<?php
use Dez\Utils\HTML;
?>
<style>

    .sy-debug-wrap {
        position: relative;
        font-size: 10px;
    }

    .debug {
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

    .debug li {
        display: inline-block;
        text-shadow: 1px 1px 1px #fff;
    }

    .debug .system-info {
        float: right;
        margin-right: 10px;
    }

    .debug .debug-name {
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

    .debug-wrap pre {
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
        display: none;
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

    .debug-content h3 {
        margin: 20px;
        color: #272727;
        font-size: 20px;
        text-shadow: 1px 1px 1px #fff;
    }

    .debug-content-box {
        margin: 20px;
        white-space: pre-wrap;
        font-size: 12px;
        font-family: 'courier new';
        border: 1px solid #B1B1B1;
        padding: 20px;
        border-radius: 4px;
        background: #FFF;
    }

</style>
<script>
    var debugData               = [];
    debugData['sql-data']       = <?= json_encode( join( "\n\n---\n\n", $data['sql'] ) ) ?>;
    debugData['request-data']   = <?= json_encode( var_export( $_REQUEST, true ) ) ?>;
    debugData['session-data']   = <?= json_encode( var_export( $_SESSION, true ) ) ?>;
    debugData['dump-data']      = <?= json_encode( var_export( $data['dump'], true ) ) ?>;
    DOM.ready(function(){
        DOM('#debug-close-button').click(function(e){ DOM('#debug-content').hide(); });
        DOM('.debug-links a').click(function(e){
            DOM('#debug-content').show();
            var self = DOM(e.target);
            if( debugData[self.data( 'name' )] ) {
                DOM('.debug-content h3').html( self.html() );
                DOM('.debug-content-box').html(debugData[self.data( 'name' )]);
            }
        });
    });
</script>
<div class="sy-debug-wrap">

    <div class="debug-content" id="debug-content">
        <h3>Title...</h3>
        <pre class="debug-content-box"></pre>
        <a id="debug-close-button" class="close" href="javascript: return false;">Close [X]</a>
    </div>

    <div class="debug">

        <ul class="debug-links">
            <li><span class="debug-name"><?php print $data['debugger_name']; ?></span></li>
            <li><a data-name="sql-data" href="javascript:void(0)">SQL <b>(<?= count( $data['sql'] ) ?>)</b></a></li>
            <li><a data-name="request-data" href="javascript:void(0)">Request</a></li>
            <li><a data-name="session-data" href="javascript:void(0)">Session</a></li>
            <li><a data-name="dump-data" href="javascript:void(0)">Dump</a></li>
        </ul>

        <ul class="system-info">
            <li><b><?php print \Dez::poweredBy(); ?></b> | </li>
            <li>ENV: <b><?php print \Dez::env(); ?></b> | </li>
            <li>Memory: <b><?php print \Dez::getMemoryUse(); ?>kB</b> | </li>
            <li>Time: <b><?php print \Dez::getTimeDiff(); ?>s</b></li>
        </ul>
        <div style="clear: both;"></div>
    </div>
</div>