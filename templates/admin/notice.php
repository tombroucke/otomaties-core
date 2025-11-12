<div class="notice notice-<?php echo esc_attr(($type ?? 'warning')); ?>">
	<p><?php echo wp_kses($message, $allowedHtml); ?></p>
</div>
