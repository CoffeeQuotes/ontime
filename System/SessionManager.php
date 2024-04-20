<?php 
namespace System; 
class SessionManager 
{
	private $sessionName; 

	public function __construct($sessionName = 'ontime_session') 
	{
		$this->sessionName = $sessionName;
		$this->startSession();
	}	

	public function startSession()
	{
		if(session_status() == PHP_SESSION_NONE) {
			session_name($this->sessionName);
			session_start();
		}
	}

	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public function get($key) 
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null; 
	}

	public function delete($key) 
	{
		unset($_SESSION[$key]); 
	}

	public function destroy() 
	{
		session_destroy();
	}

	public function regenerate()
	{
		session_regenerate_id(true);
	}
}