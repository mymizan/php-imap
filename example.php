<?php
require __DIR__ . '/imap.php';

$conn = new IMAP($your_server_address, $port, $username, $password, $require_ssl);

if ($conn) {
	$emails = $imap->getRecentMsgs(10);
	//parse the email body here and act on the result
	var_dump($emails);
}