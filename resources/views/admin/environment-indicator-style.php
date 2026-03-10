<?php defined('ABSPATH') || exit; ?>

<style>
	#wpadminbar .environment-indicator > .ab-item {
		background-color: <?php echo $backgroundColor; ?>;
		color: #fff;
	}
	#wpadminbar .environment-indicator > .ab-item:before {
		content: "\f177";
		top: 2px;
	}
</style>
