<?php
function format_date($date)
{
	return date('d M Y', strtotime($date));
}

function format_currency($amount)
{
	return 'Rp ' . number_format($amount, 0, ',', '.');
}
if (!function_exists('status_icon')) {
	function status_icon($status)
	{
		$class = (!empty($status)) ? 'text-primary' : 'text-danger';
		$icon = (!empty($status)) ? '✅' : '❌';
		return '<span class="' . $class . '">' . $icon . '</span>';
	}
}
if (!function_exists('log_action')) {
	function log_action($kategoriLogId, $jenisUser, $idUser, $logMessage)
	{
		$ci = &get_instance();
		$ci->load->model('LogModel');
		$ci->LogModel->logAction($kategoriLogId, $jenisUser, $idUser, $logMessage);
	}
}
