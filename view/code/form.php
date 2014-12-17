<h1>Добавить</h1>
<div class="form">
    <form action="<?=url()?>" method="post">

        <div>
            <label for="code-title">Заголовок:
                <input
                    name="code[title]"
                    type="text"
                    id="code-title"
                    placeholder="Заголовок..."
                    value="<?=( isset( $data['title'] ) ? $data['title'] : null )?>"
                    />
            </label>
        </div>

        <div>
            <label for="language">Язык:
                <select name="code[language]" id="language">
                    <option value="php">PHP</option>
                    <option value="js">JavaScript</option>
                    <option value="html">HTML</option>
                    <option value="css">CSS</option>
                </select>
            </label>
        </div>

        <div>
            <label for="source">Код:
                <textarea placeholder="Вставьте код сюда" name="code[source]" id="source"><?=( isset( $data['source'] ) ? $data['source'] : null )?></textarea>
            </label>
        </div>

        <div>
            <label for="password">Пароль:
                <input
                    name="code[password]"
                    type="password"
                    id="password"
                    placeholder="Введите пароль"
                    value="<?=( isset( $data['password'] ) ? $data['password'] : null )?>"
                    />
            </label>
        </div>

        <input type="submit" value="Добавить"/>
    </form>
</div>