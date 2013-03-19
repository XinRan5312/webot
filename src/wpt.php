<?php
	include_once('config.php');
	include_once('saetv2.ex.class.php');

	include_once('WeiboPoster.php');
        $obj = new WeiboPoster(true);
        $obj->update('a test message');
?>