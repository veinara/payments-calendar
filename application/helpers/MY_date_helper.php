<?php

function date_mysql2custom($date_string, $custom_format=FALSE)
{
	if (! $date_string) {
		return FALSE;
	}

	if (!$custom_format) {
		$custom_format = config_item('inv_date_format');
	}

	$dt = DateTime::createFromFormat('Y-m-d', $date_string);
	if ($dt) {
		return $dt->format($custom_format);
	} else {
		return FALSE;
	}
}

function date_custom2mysql($date_string, $custom_format=FALSE)
{
	if (! $date_string) {
		return FALSE;
	}

	if (!$custom_format) {
		$custom_format = config_item('inv_date_format');
	}
	$dt = DateTime::createFromFormat($custom_format, $date_string);
	if ($dt) {
		return $dt->format('Y-m-d');
	} else {
		return FALSE;
	}
}