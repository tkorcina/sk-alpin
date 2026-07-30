<?php

if (substr($_SERVER['REMOTE_ADDR'], 0, strrpos($_SERVER['REMOTE_ADDR'], '.')) !== substr($_SERVER['SERVER_ADDR'], 0, strrpos($_SERVER['SERVER_ADDR'], '.'))) {
	die('REMOTE_ADDR !== SERVER_ADDR');
}

$rate = get_used_opcache_rate();

if (
	$rate >= 0.7
) {
	mail('tomaskorcina@seznam.cz', 'OPCache alert on server ' . gethostname(), 'Rate: ' . $rate);

	if ($rate >= 0.95) {
		opcache_reset();
	}
}

function get_used_opcache_rate() {
	$status = opcache_get_status(FALSE);

	return max(
		$status['memory_usage']['used_memory'] / ($status['memory_usage']['used_memory'] + $status['memory_usage']['free_memory']),
		$status['interned_strings_usage']['used_memory'] / ($status['interned_strings_usage']['used_memory'] + $status['interned_strings_usage']['free_memory']),
		$status['opcache_statistics']['num_cached_keys'] / $status['opcache_statistics']['max_cached_keys']
	);
}