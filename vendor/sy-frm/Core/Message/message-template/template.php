<!-- Message Box -->
<?php if( isset( $stack['success'] ) && count( $stack['success'] ) > 0 ): ?>
    <div class="sy-message-box sy-message-success">
        <ul>
        <? foreach( $stack['success'] as $message ): ?>
            <li><?= $message ?></li>
        <? endforeach; unset( $message ); ?>
        </ul>
    </div>
<?php endif; ?>
<?php if( isset( $stack['info'] ) && count( $stack['info'] ) > 0 ): ?>
    <div class="sy-message-box sy-message-info">
        <ul>
        <? foreach( $stack['info'] as $message ): ?>
            <li><?= $message ?></li>
        <? endforeach; unset( $message ); ?>
        </ul>
    </div>
<?php endif; ?>
<!-- END Message Box -->