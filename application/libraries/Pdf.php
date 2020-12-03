<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Pdf {

	public function __construct()
	{

		$CI = & get_instance();
		log_message('Debug', 'mPDF class is loaded.');

	}

	function load($param = NULL)
	{

		include_once APPPATH.'/third_party/mpdf/mpdf.php';
		if ($param == NULL)
		{
			$param = "'','A4',0,'',15,15,35,26,9,9,'P'";
			// $param = null;
		}
		$param = explode(',', $param);
		return new mPDF($param[0], 
						$param[1], 
						$param[2], 
						$param[3], 
						$param[4], 
						$param[5], 
						$param[6], 
						$param[7], 
						$param[8],
						$param[9],
						$param[10]);

	}

}