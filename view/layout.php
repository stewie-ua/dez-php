<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>layout.php <?=( isset( $title ) ? $title : null )?></title>
    </head>
    <body>
        <?=( isset( $error_block ) ? $error_block : null )?>
        <?=( isset( $message_block ) ? $message_block : null )?>
        <?=( isset( $content ) ? $content : null )?>
    </body>
</html>