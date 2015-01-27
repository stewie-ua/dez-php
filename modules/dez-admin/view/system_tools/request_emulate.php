<script>
    app.DOM.ready(function( domJS ){
        domJS('#send_request').on('click', function(){
            app.ajax({
                method:     domJS('#request_method').val(),
                url:        domJS('#request_url').val()
            }).then(function(response) {
                domJS('#request_emulate_response').html(response);
            });
        });
    });
</script>
<table class="main">

    <thead>
    <tr>
        <td colspan="2">
            Создание запросов
        </td>
    </tr>
    </thead>

    <tr>
        <td>
            Адрес
        </td>
        <td>
            <input type="text" name="request_url" id="request_url" value="/" />
        </td>
    </tr>

    <tr>
        <td>
            Метод запороса
        </td>
        <td>
            <?= \Dez\Utils\HTML::select( [
                'GET'       => 'GET',
                'POST'      => 'POST',
                'PUT'       => 'PUT',
                'DELETE'    => 'DELETE',
                'PATCH'     => 'PATCH',
                'CUSTOM1'   => 'CUSTOM1',
                'CUSTOM2'   => 'CUSTOM2',
                'CUSTOM3'   => 'CUSTOM3'
            ], 'request_method', null, [ 'id' => 'request_method' ] ); ?>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <input type="button" id="send_request" value="Отправить"/>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <div>
                <pre id="request_emulate_response">response here...</pre>
            </div>
        </td>
    </tr>

</table>