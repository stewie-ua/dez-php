<script src="<?=\Dez\Core\URI::base()?>media/js/redactor/redactor.min.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/redactor/ru.js"></script>
<link rel="stylesheet" href="<?=\Dez\Core\URI::base()?>media/js/redactor/redactor.css"/>
<link rel="stylesheet" href="<?=\Dez\Core\URI::base()?>media/js/redactor/custom.css"/>
<script>
    $(function(){
        $('#post-content').redactor({
            minHeight: 300,
            maxHeight: 800,
            lang: 'ru',
            imageUpload: '<?=\Dez\Core\URI::base()?>common/uploadImage'
        });
    });
</script>
<h1>Добавление иструмент</h1>
<div class="form">
    <form action="<?=url()?>" method="post">

        <div>
            <label for="title">Заголовок:
                <input
                    name="tool[title]"
                    type="text"
                    id="title"
                    placeholder="Заголовок..."
                    value="<?=( isset( $data['title'] ) ? $data['title'] : null )?>"
                    />
            </label>
        </div>


        <div>
            <label for="login">Описание:
                <textarea name="tool[description]" id="post-content"><?=( isset( $data['description'] ) ? $data['description'] : null )?></textarea>
            </label>
        </div>

        <div>
            <label for="tags">URL:
                <input
                    name="tool[url]"
                    type="text"
                    id="tags"
                    placeholder="tool_name"
                    value="<?=( isset( $data['url'] ) ? $data['url'] : null )?>"
                    />
            </label>
        </div>

        <input type="submit" value="Добавить"/>
    </form>
</div>