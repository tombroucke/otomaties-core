<?php defined('ABSPATH') || exit; ?>

<script>
	let revisionData = <?php echo json_encode($releaseInformation); ?>;
	for(const key in revisionData) {
		console.log(`${key}: ${revisionData[key]}`);
	}
</script>
