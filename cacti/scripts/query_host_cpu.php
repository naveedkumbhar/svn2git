<?php

include(dirname(__FILE__) . "/../include/config.php");
include(dirname(__FILE__) . "/../include/snmp_functions.php");

$oids = array(
	"index" => ".1.3.6.1.2.1.25.3.3.1",
	"usage" => ".1.3.6.1.2.1.25.3.3.1"
	);

$hostname = $_SERVER["argv"][1];
$snmp_community = $_SERVER["argv"][2];
$cmd = $_SERVER["argv"][3];

if ($cmd == "index") {
	$arr_index = get_indexes($hostname, $snmp_community);
	
	for ($i=0;($i<sizeof($arr_index));$i++) {
		print $arr_index[$i] . "\n";
	}
}elseif ($cmd == "query") {
	$arg = $_SERVER["argv"][4];
	
	$arr_index = get_indexes($hostname, $snmp_community);
	$arr = get_cpu_usage($hostname, $snmp_community);
	
	for ($i=0;($i<sizeof($arr_index));$i++) {
		if ($arg == "usage") {
			print $arr_index[$i] . "!" . $arr[$i] . "\n";
		}elseif ($arg == "index") {
			print $arr_index[$i] . "!" . $arr_index[$i] . "\n";
		}
	}
}elseif ($cmd == "get") {
	$arg = $_SERVER["argv"][4];
	$index = $_SERVER["argv"][5];
	
	$arr_index = get_indexes($hostname, $snmp_community);
	$arr = get_cpu_usage($hostname, $snmp_community);
	
	if (isset($arr_index[$index])) {
		print $arr[$index];
	}
}

function get_cpu_usage($hostname, $snmp_community) {
	$arr = reindex(cacti_snmp_walk($hostname, $snmp_community, ".1.3.6.1.2.1.25.3.3.1", "1", "", ""));
	$return_arr = array();
	
	$j = 0;
	
	for ($i=0;($i<sizeof($arr));$i++) {
		if (ereg("^[0-9]+$", $arr[$i])) {
			$return_arr[$j] = $arr[$i];
			$j++;
		}
	}
	
	return $return_arr;
}

function get_indexes($hostname, $snmp_community) {
	$arr = reindex(cacti_snmp_walk($hostname, $snmp_community, ".1.3.6.1.2.1.25.3.3.1", "1", "", ""));
	$return_arr = array();
	
	$j = 0;
	
	for ($i=0;($i<sizeof($arr));$i++) {
		if (ereg("^[0-9]+$", $arr[$i])) {
			$return_arr[$j] = $j;
			$j++;
		}
	}
	
	return $return_arr;
}

function reindex($arr) {
	$return_arr = array();
	
	for ($i=0;($i<sizeof($arr));$i++) {
		$return_arr[$i] = $arr[$i]["value"];
	}
	
	return $return_arr;
}

?>
