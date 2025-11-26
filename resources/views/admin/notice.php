<?php defined('ABSPATH') || exit; ?>

<div class="notice notice-<?php echo esc_attr(($type ?? 'warning')); ?>">
	<?php echo wpautop(wp_kses($message, $allowedHtml)); ?>
</div>
