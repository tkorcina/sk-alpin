<?php

if (substr($_SERVER['REMOTE_ADDR'], 0, strrpos($_SERVER['REMOTE_ADDR'], '.')) !== substr($_SERVER['SERVER_ADDR'], 0, strrpos($_SERVER['SERVER_ADDR'], '.'))) {
	die('REMOTE_ADDR !== SERVER_ADDR');
}

$opcacheInfo = opcache_get_status();
if ($opcacheInfo === FALSE) {
	die('opcache get status failed');
}

$dir = realpath(__DIR__ . '/..');
foreach ($opcacheInfo['scripts'] as $key => $script) {
	if ($script['timestamp'] !== 0 && strstr($key, $dir) !== FALSE) {
		opcache_invalidate($key, TRUE);
	}
}
