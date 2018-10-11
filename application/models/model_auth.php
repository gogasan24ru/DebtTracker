<?php
class Model_Auth extends Model
{

	public function authSuccess($username,$password)
	{
		session_start();
		//check_auth();
		//$data['avaliableParticipants']=Array();
		require 'application/config.php';
		$db = new SafeMySQL(array('user' => $DBUSER,'pass' => $DBPASS, 'db' => $DB,'charset' => 'utf8'));
		$users = $db->getAll("SELECT id,username,realname FROM users WHERE password=?s AND username=?s",md5($password),$username);

		if(isset($users[0])) 
		{
			$_SESSION['selfid']=$users[0]['id'];
			$_SESSION['username']=$users[0]['username'];
			$_SESSION['auth']=md5(md5($users[0]['id']).md5($users[0]['username']).md5($users[0]['realname']));
			//die(var_dump($_SESSION));
			logger($users[0]['username']."logged in");
			return true;
		}

		return false;
	}

	public function logout()
	{
			session_start();
                	check_auth();

			logger($_SESSION['username']."logged out");
                       $_SESSION['selfid']="";
                       $_SESSION['auth']="";
			//logger($_SESSION['username']."logged out");
			$_SESSION['username']="";
		session_start();
		destroy_session();
		//session_destroy();
		header('Location:');
	}

}


?>

