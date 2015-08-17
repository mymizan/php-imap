<?php
/*
|--------------------------------------------------------------------------
| PHP IMAP Class
|--------------------------------------------------------------------------
|
| I have tried to map most of the common PHP IMAP functions into this class
|
| @nauthor M Yakub Mizan <ctgcoder@gmail.com>
| @version 1.0
 */
class IMAP {
	public $connection = null;
	public $default_size = 20;

	protected $address;
	protected $port;
	protected $require_ssl;
	protected $username;
	protected $password;
	protected $extra_flags;

	/*
	| @param $address server address
	| @param $port port
	| @param $username username
	| @param $password password
	| @param true|false $require_ssl use ssl or not
	| @param $extra_flags Pass the extra IMAP flags
	 */
	public function __construct($address, $port, $username, $password, $require_ssl = true, $extra_flags = '') {
		$this->address = $address;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;
		$this->require_ssl = $require_ssl;
		$this->extra_flags = $extra_flags;
		$this->connect();
	}

	/*
	| Authenticate with the imap server
	|
	| @return boolean
	 */
	public function connect() {
		$ssl = '';
		if ($this->require_ssl) {
			$ssl = '/ssl';
		}

		$this->connection = imap_open("{{$this->address}:{$this->port}/imap{$ssl}{extra_flags}}",
			$this->username, $this->password);

		if ($this->connection) {
			return true;
		}

		return false;
	}

	/*
	| Ping the stream to see if it's still alive.
	| You can use this method to check if the user is still loggedin.
	| @return true|false
	 */
	public function ping() {
		return imap_ping($this->connection);
	}

	public function fetchEmails() {
		return imap_check($this->connection);

	}

	public function listMailboxes($pattern = "*") {
		if ($this->connection) {
			return imap_list($this->connection, "{{$this->address}:{$this->port}}", $pattern);
		}
		return null;
	}

	public function getRecentMsgs($number) {
		$email_list = array();
		$emails = imap_search($this->connection, 'UNSEEN'); //get unread messages
		foreach ($emails as $email) {
			$email_list[] = imap_body($this->connection, $email, FT_PEEK);
		}
		return $email_list;
	}
}
