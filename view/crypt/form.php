<h1>Шифрование</h1>
<div class="form">
    <form action="<?=url()?>" method="post">

        <div>
            <label for="login">Исходный текст:
                <textarea name="crypt[text]" id="crypt-content"><?=( isset( $data['text'] ) ? $data['text'] : null )?></textarea>
            </label>
        </div>

        <div>
            <label for="key">Ключ:
                <input
                    name="crypt[key]"
                    type="text"
                    id="key"
                    placeholder="Введите ключ..."
                    value="<?=( isset( $data['key'] ) ? $data['key'] : null )?>"
                    />
            </label>
        </div>

        <div>
            <label for="key">Режим:
                <?=\Dez\Utils\HTML::select( [
                    'encode'    => 'Шифровать',
                    'decode'    => 'Разшифровать'
                ], 'crypt[mode]', isset( $data['mode'] ) ? $data['mode'] : null )?>
            </label>
        </div>

        <div>
            <label for="login">Результат:
                <textarea readonly="readonly"><?=$result?></textarea>
            </label>
        </div>

        <input type="submit" value="Добавить"/>
    </form>
</div>