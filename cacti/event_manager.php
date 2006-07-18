<?php
/*
 +-------------------------------------------------------------------------+
 | Copyright (C) 2006 The Cacti Group                                      |
 |                                                                         |
 | This program is free software; you can redistribute it and/or           |
 | modify it under the terms of the GNU General Public License             |
 | as published by the Free Software Foundation; either version 2          |
 | of the License, or (at your option) any later version.                  |
 |                                                                         |
 | This program is distributed in the hope that it will be useful,         |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of          |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           |
 | GNU General Public License for more details.                            |
 +-------------------------------------------------------------------------+
 | Cacti: The Complete RRDTool-based Graphing Solution                     |
 +-------------------------------------------------------------------------+
 | This code is designed, written, and maintained by the Cacti Group. See  |
 | about.php and/or the AUTHORS file for specific developer information.   |
 +-------------------------------------------------------------------------+
 | http://www.cacti.net/                                                   |
 +-------------------------------------------------------------------------+
*/

$no_http_headers = true;

require(dirname(__FILE__) . "/include/global.php");

print "Contruct a sample log message and insert it into the queue<br>";
api_event_insert("cacti_log", array("severity" => SEV_EMERGENCY, "facility"=>FACIL_WEBUI, "message"=>"testing"));


print "Set the status on Event Items to allow processing<br>";
/* Set the status to show which events are being processed */
api_event_set_status();

print "Checking the Event Queue for items<br>";
/* Get all events so we can begin processing */
$events = api_event_list(array('status'=>1));

print "Processing all items<br>";
foreach ($events as $event) {
	api_event_process ($event['id']);
	print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Processed event " . $event['id'] . '<br>';
}

print "Remove all processed events from the queue<br>";
/* Remove all events that were set to be processed */
api_event_removed_processed();


?>