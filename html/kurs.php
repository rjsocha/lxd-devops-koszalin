<?php

function process_kurs($dir,&$arr) {
	if(!file_exists('kurs/' . $dir . "/kurs.ini")) {
		return 0;
	}
	$meta=parse_ini_file('kurs/' . $dir."/kurs.ini",true);

	if ($handle = opendir('kurs/' . $dir)) {
		$files=array();
    		while (false !== ($entry = readdir($handle))) {
			if(preg_match("/^[a-zA-Z0-9_-]+\.html$/",$entry)) {
				$files[]=$entry;
			}
		}
		sort($files);
	}
	$arr=$meta;
	$arr['q']=$files;
	return 1;
}
	$kursy=array();
	if ($handle = opendir('kurs/')) {
    		while (false !== ($entry = readdir($handle))) {
			if($entry=="." || $entry=="..") {
				continue;
			}
			if(!is_dir('kurs/' . $entry)) {
				continue;
			} else {
				$arr=array();
				$r=process_kurs($entry,$arr);
				if($r) {
					$arr['kurs']['path']="/kurs/$entry/";
					$kursy[]=$arr;
				}
			}
			
		}
	} else {
		die(".... :(\n");
	}
	if(count($kursy)) {
		echo	json_encode($kursy);
	}
