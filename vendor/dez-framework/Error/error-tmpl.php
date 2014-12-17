<!-- Error box -->
<?php if( sizeOf( $stack['critical'] ) > 0 ): ?>
	<div class="sy-errors sy-errors-critical">
		<ul>
			<?php foreach( $stack['critical'] as $criticalError ): ?>
				<li><?php print $criticalError; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<?php if( sizeOf( $stack['warning'] ) > 0 ): ?>
	<div class="sy-errors sy-errors-warning">
		<ul>
			<?php foreach( $stack['warning'] as $warningError ): ?>
				<li><?php print $warningError; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<?php if( sizeOf( $stack['notify'] ) > 0 ): ?>
	<div class="sy-errors sy-errors-notify">
		<ul>
			<?php foreach( $stack['notify'] as $notifyError ): ?>
				<li><?php print $notifyError; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<!-- END Error box -->