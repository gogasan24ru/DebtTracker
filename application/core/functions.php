<?php
function whoami()
{
         global $db;
        $users = $db->getAll("SELECT id,username,realname FROM users WHERE id=?s",$_SESSION['selfid']);
	if(isset($users[0]['realname']))
        return $users[0]['realname'];
	else
	return "UNKNOWN";
}


function destroy_session()
{


                                if(isset($_COOKIE["PHPSESSID"]))
                                {
					if (session_status() == PHP_SESSION_NONE) {
					    session_start();
					}
                                        $_COOKIE=Array();
					$_SESSION=Array();
                                        session_destroy();
                                };
}

function logger($m)
{
	global $db;
	$sql='INSERT INTO logs  SET ?u';
        $responce=$db->query($sql,Array('executor'=>whoami(),'message'=>$m));

}


function check_auth($headers=true)
{
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

			global $db;
			if(!isset($_SESSION['auth'])){
				if($headers)Header('Location:/auth/login');
				destroy_session();
				return false;
			}

			if($_SESSION['auth']===""){
				if($headers)Header('Location:/auth/login');
				destroy_session();
				return false;
			}
			$users = $db->getAll("SELECT id,username,realname FROM users WHERE id=?s",$_SESSION['selfid']);

			$checksum=md5(md5($users[0]['id']).md5($users[0]['username']).md5($users[0]['realname']));

			if($_SESSION['auth']!=$checksum)
			{
				if($headers)Header('Location:/auth/login');
				destroy_session();
				return false;
			}
	return true;
};


?>
