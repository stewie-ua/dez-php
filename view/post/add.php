<script src="<?=\Dez\Core\URI::base()?>media/js/redactor/redactor.min.js"></script>
<script src="<?=\Dez\Core\URI::base()?>media/js/redactor/ru.js"></script>
<link rel="stylesheet" href="<?=\Dez\Core\URI::base()?>media/js/redactor/redactor.css"/>
<link rel="stylesheet" href="<?=\Dez\Core\URI::base()?>media/js/redactor/custom.css"/>
<script>
    $(function(){
        $('#post-content').redactor({
            minHeight: 300,
            maxHeight: 800,
            placeholder: 'Текст публикации...',
            lang: 'ru',
            imageUpload: '<?=\Dez\Core\URI::base()?>common/uploadImage'
        });
    });
</script>
<h1>Добавление публикации</h1>
<div class="form">
    <form action="<?=url()?>" method="post">

        <div>
            <label for="title">Заголовок:
                <input
                    name="post[title]"
                    type="text"
                    id="title"
                    placeholder="Заголовок..."
                    value="<?=( isset( $data['title'] ) ? $data['title'] : null )?>"
                    />
            </label>
        </div>

        <div>
            <label for="login">Текст публикации:
                <textarea name="post[text]" id="post-content"><?=( isset( $data['text'] ) ? $data['text'] : null )?></textarea>
            </label>
        </div>

        <div>
            <label for="tags">Теги:
                <input
                    name="post[tags]"
                    type="text"
                    id="tags"
                    placeholder="php, js, css"
                    value="<?=( isset( $data['tags'] ) ? $data['tags'] : null )?>"
                    />
            </label>
        </div>

        <input type="submit" value="Добавить"/>
    </form>
</div>