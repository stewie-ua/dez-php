<?php use \Dez\Utils\Crypt; use \Dez\Utils\NumConv; ?>

<!--CodeMirror-->

<link rel="stylesheet" href="<?=\Dez\Core\URI::base()?>media/js/codemirror/codemirror.css" />
<link rel="stylesheet" href="<?=\Dez\Core\URI::base()?>media/js/codemirror/theme/default.css" />

<style type="text/css">
    .CodeMirror-scroll {
        height: auto;
        overflow-y: hidden;
        overflow-x: auto;
        font-size: 12px;
        line-height: 20px;
    }
</style>

<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/codemirror.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/util/runmode.js"></script>
<!-- all modes -->
<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/mode/xml/xml.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/mode/javascript/javascript.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/mode/css/css.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/mode/clike/clike.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/codemirror/mode/php/php.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var code    = $('#source-input');
        var source  = $('#source');
        var cm      = CodeMirror( code[0], {
            value:          source.val(),
            mode:           '<?php print $mime[$item->getLanguage()]; ?>',
            lineNumbers:    true,
            readOnly:       true
        });
        cm.setOption( 'theme', 'default' );
    });
</script>

<!--CodeMirror-->

<h2>Source #<?=$item->id()?> [<?=$item->getTitle()?>]</h2>
<div class="form">

    <a class="go-links" href="<?=url()?>#links">к ссылкам</a>

    <div>
        <label for="source">
            <div class="code-body" id="source-input"></div>
            <textarea style="display: none" id="source"><?=$item->getSource()?></textarea>
        </label>
    </div>

    <a name="links"></a>

    <div>
        <label for="password">URL:
            <input
                type="text"
                value="<?=url( 'k/'. NumConv::instance()->encode( $item->id() ), [], true )?>"
                />
        </label>
    </div>

    <div>
        <label for="password">Share URL:
            <input
                type="text"
                value="<?=url(
                    'k/'. NumConv::instance()->encode( $item->id() ),
                    [
                        'share-link' => Crypt::instance()->encode( join( ':', [ $item->id(), $item->getAuthorId() ] ), 'kill.li' )
                    ], true )?>"
                />
        </label>
    </div>

</div>