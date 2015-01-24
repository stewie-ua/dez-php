<script>
    app.DOM.ready(function( scope ){

        scope('#send_request').on('click', function(){
            app.ajax({
                method: scope('#request_method')[0].value,
                url:    scope('#request_url')[0].value,
                type: 'json'
            }).then(function(response) {
                console.log(response)
                //scope('#request_emulate_response').html(response);
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