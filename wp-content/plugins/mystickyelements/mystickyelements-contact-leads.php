<?php

$path1 = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
include($path1.'wp-load.php');


if (current_user_can('activate_plugins')) {
	
	$file = "mystickyelements_contact_leads.csv";
	$fp = fopen($file, "a")or die("Error Couldn't open $file for writing!");
	
    global $wpdb;
    $contact_lists_table = $wpdb->prefix.'mystickyelement_contact_lists';
    $contact_lists_to_write = $wpdb->get_results( "SELECT * FROM $contact_lists_table");
	$all_data = '';
	foreach ($contact_lists_to_write as $res) {
		$res_ID = $res->ID;
		$res_name = $res->contact_name;
		$res_phone = $res->contact_phone;
		$res_email = $res->contact_email;
		$res_option = $res->contact_option;
		$res_message = $res->contact_message;
		$res_message_date = $res->message_date;
		$page_link = $res->page_link;		

		$current_row = $res_ID.' , '.$res_name.' , '.$res_phone.' , '.$res_email.' , '.$res_option.' , '.$res_message.' , '.$res_message_date. PHP_EOL;
		$all_data = $all_data." ".$current_row . "\r\n";
		$fields = array($res_ID, $res_name, $res_phone, $res_email, $res_option, $res_message, $res_message_date,$page_link);
		fputcsv($fp, $fields);
	}


	
	//fwrite($fp, $all_data)or die("Error Couldn't write values to file!"); 
	fclose($fp); 

	ignore_user_abort(true);
	set_time_limit(0); // disable the time limit for this script

	 // change the path to fit your websites document structure
	$path = plugins_url('/',__FILE__); // change the path to fit your websites document structure
	$dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $_GET['download_file']); // simple file name validation
	$dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters
	$fullPath = $path.$dl_file;

	if ($fd = fopen ($fullPath, "r")) {
		$path_parts = pathinfo($fullPath);
		$ext = strtolower($path_parts["extension"]);
		switch ($ext) {
			case "csv":
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
			break;
			// add more headers for other content types here
			default;
			header("Content-type: application/octet-stream");
			header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
			break;
		}
		header("Cache-control: private"); //use this to open files directly
		while(!feof($fd)) {
			$buffer = fread($fd, 2048);
			echo $buffer;
		}
	}
	fclose ($fd);

	$file = "mystickyelements_contact_leads.csv";
	unlink($file);

	exit;

}