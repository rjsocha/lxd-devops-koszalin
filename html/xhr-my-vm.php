<?php
	require('f.php');
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('HTTP/1.0 403 Forbidden');
		die();
	}
	$ns=$_SESSION['namespace'];
	$ns_dir = "/home/socha/.vm/namespace/" . $ns;

	$p=get_pool($ns);
	sort($p);
	$ret = array();
	$idx=0;
	foreach($p as $vm) {
		$vi = get_vm_info($vm);
		if(count($vi)<1) continue;
		$vi = $vi[0];
		$status = strtolower($vi['status']);
		$ret[$idx]['have_ip6']=0;
		$ret[$idx]['have_ip4']=0;
		$ret[$idx]['name']=$vm;
		$ret[$idx]['state']=$status;
		$ret[$idx]['domain']='lxd.nauka.ga';
		$ret[$idx]['architecture']=$vi['architecture'];
		$ret[$idx]['created_at']=strtotime($vi['created_at']);
		$ret[$idx]['id']=md5($ret[$idx]['created_at'] . $ret[$idx]['name']);
		if($status=="running") {
			$has_ip6_global=0;
			$ret[$idx]['data']['memory']=$vi['state']['memory'];
			foreach($vi['state']['network'] as $net => $key) {
				if($net == "lo" || $net == "sit0")
					continue;
				if(isset($key['host_name'])) unset($key['host_name']);
				$ret[$idx]['data']['network'][$net]=$key;
			}
			if(count($ret[$idx]['data']['network'])) {
				foreach($ret[$idx]['data']['network'] as $net_if) {
					if(isset($net_if['addresses'])) {
						foreach($net_if['addresses'] as $addr) {
							if($addr['scope']=="global") {
								if($addr['family']=="inet6") $has_ip6_global=1;
								if($addr['family']=="inet") $has_ip4_global=1;
								$ret[$idx]['network'][$addr['family']][]=array("address"=>$addr['address'],"netmask"=>$addr['netmask']);
							}
					       }
					}
				}
			}
			$handle = fopen($ns_dir . "/vm/" . $vm, "r");
			$vm_pass="-";
			if ($handle) {
				while (($line = fgets($handle)) !== false) {
					if(preg_match("/^LXD_PASS=(.+)$/",$line,$match)) {
						$vm_pass = $match[1];
						}
				}
				fclose($handle);
			}
			$ret[$idx]['data']['os']['distribution']='Ubuntu 18.04 LTS';
			$ret[$idx]['data']['os']['username']='ubuntu';
			$ret[$idx]['data']['os']['password']=$vm_pass;
			if($has_ip6_global) { 
				$ret[$idx]['have_ip6']=1;
			}
			if($has_ip4_global) { 
				$ret[$idx]['have_ip4']=1;
			}
			if(!$has_ip6_global || !$has_ip4_global) {
				$ret[$idx]['state'] = 'booting';
			}

		}
		$idx++;
	}
	//sleep(2);
	echo json_encode($ret);
