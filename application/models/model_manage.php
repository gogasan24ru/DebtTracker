<?php
class Model_Manage extends Model
{

	private function modifyDebtSumm($id,$summ)
	{
		global $db;
		$args=Array('summ'=>$summ);
		$db->query("UPDATE debts SET ?u WHERE id = ?s",$args,$id);
	}


        public function removePay($id){
                global $db;
                session_start();
                check_auth();

		$sql='DELETE FROM payments  WHERE ?u';
                $responce=$db->query($sql,
                                Array( 'id' => $id )
                        );

                $sql='DELETE FROM debts  WHERE ?u';
                $responce=$db->query($sql,
                                Array( 'source' => $id )
                        );
		logger("removed some payment");

}
	public function setNewName($name){
		global $db;
                session_start();
                check_auth();
		logger("name update initiated");
                $sql='UPDATE users  SET ?u WHERE ?u';
                $responce=$db->query($sql,
                                Array( 'realname' => $name),
                                Array( 'id' => $_SESSION['selfid'] )
                        );
		logger("name update done");
	}

        public function setNewPassword($oldPass,$newPass){
                global $db;
                session_start();
                check_auth();
		logger("password update sequence begun");
		$users = $db->getAll("SELECT password FROM users WHERE id = ?s",$_SESSION['selfid']);
		if($users[0]['password']===(md5($oldPass)) ){
	                $sql='UPDATE users  SET ?u WHERE ?u';
	                $responce=$db->query($sql,
        	                        Array( 'password' => md5($newPass)),
                	                Array( 'id' => $_SESSION['selfid'] )
                        	);
			return true;
		}else return false;
        }


	public function get_participants()
	{
		global $db;
		session_start();
//		require 'application/config.php';
//		$db = new SafeMySQL(array('user' => $DBUSER,'pass' => $DBPASS, 'db' => $DB,'charset' => 'utf8'));
		check_auth();
		$data['avaliableParticipants']=Array();
		$users = $db->getAll("SELECT id,realname FROM users");

	        function self_filter($var){return($var['id']!=$_SESSION['selfid']);} ///$_SESSION['selfid']
		$users = array_filter($users,"self_filter");
		//var_dump($users);
		$data['avaliableParticipants']=$users;

		return $data;
	}

	public function getAccountInfo()
	{
                global $db;
                session_start();
                check_auth();

		$data=$db->getAll("SELECT realname,username FROM users WHERE id = ?s",$_SESSION['selfid'])[0];
		return $data;
	}

	public function reclaimDebt($id,$mess,$internal=true){
		global $db;
                if($external){
			session_start();
        	        check_auth();
		}
		logger("Debt ".$id." reclaim initiated with comment ".$mess);
		$sql='UPDATE debts  SET ?u WHERE ?u';
                $responce=$db->query($sql,
				Array( 'comment'=>$mess,'ispaid' => 1, 'payday' => date("Y-m-d H:i:s")),
				Array( 'id' => $id )
			);
	}
	public function getDebts()
        {
		global $db;
                session_start();
                check_auth();

		$data['debts']=$db->getAll("SELECT * FROM debts WHERE creditor=?s",$_SESSION['selfid']);

		$data['users']=$db->getAll("SELECT id,realname FROM users");
		foreach ( $data['users'] as $user)  $data['users'][$user['id']]=$user;

		$data['sources']=$db->getAll("SELECT * FROM payments WHERE owner=?s",$_SESSION['selfid']);
		foreach ( $data['sources'] as $source)  $data['sources'][$source['id']]=$source;
		return $data;
	}

	public function addPayment($data,$path="undefined")
	{
		global $db;
                session_start();
		check_auth();
		logger("new payment added");
		//$uploaddir = '/var/www/html/attachments/';
		var_dump($data);
		var_dump($path);

//		die();
		//payment processing

		//could i change keys in data? so lazy haha
		$dataArgs['owner']=$_SESSION['selfid'];
		$dataArgs['description']=$data['description'];
		$dataArgs['amount']=$data['price'];
		$dataArgs['participants']="";
		foreach ($data['participants'] as $id => $on)
			if($on==true)/* dummy check? */$dataArgs['participants'].=$id.',';
		$dataArgs['participants']=trim($dataArgs['participants'],',');
		 $dataArgs['comment']=$data['commentary'];
		 $dataArgs['attachment']=$path;
		$hash=md5($_SESSION['auth'].$path.time().$data['commentary'].$dataArgs['participants'].$data['price'].$data['description'].$_SESSION['selfid']);
		$dataArgs['hash']=$hash;
		//die(var_dump($dataArgs));
		$sql='INSERT INTO payments  SET ?u';
		$responce=$db->query($sql,$dataArgs);
		
		//die(var_dump($responce));

		//create depts
		$count=count($data['participants']);
		$perPar=1.0*$data['price']/($count+1);
		$payId="";
		$users = $db->getAll("SELECT id FROM payments where hash=?s",$hash);
		$payId=$users[0]['id'];
		//die(var_dump(get_defined_vars()));
		foreach ($data['participants'] as $id => $on)
			{
				$debtArgs['debtor']=$id;
				$debtArgs['creditor']=$_SESSION['selfid'];
				$debtArgs['source']=$payId;
				$debtArgs['summ']=$perPar;
				$sql='INSERT INTO debts  SET ?u';
                		$responce=$db->query($sql,$debtArgs);
			}

	}






        public function selfDebtReclaiming()
        {
              logger("self debt reclaiming started");
              global $db;
              $debts=$db->getAll("SELECT * FROM debts WHERE ispaid = 0");
              logger(count($debts)." debts found, scaning for ring dependencies");
              //
              foreach ($debts as $debt)
              {
                $p1id=$debt['debtor'];
                $p2id=$debt['creditor'];
                foreach ($debts as $debt2)
                {
                        if(($p1id==$debt2['creditor'])
                                &&
                           ($p2id==$debt2['debtor'])
                                &&
                           ($debt2['ispaid']==="0"))
                        {
                                logger("1nd lwl dependence foind between"
                                .$debt['id']." and ".$debt2['id']);
                                if(abs($debt['summ'])>
                                   abs($debt2['summ']))
                                {
                                        $this->reclaimDebt($debt2['id'],
                                        "ring dependence with ".
                                        $debt['id'],false);
                                        $this->modifyDebtSumm($debt['id'],
                                        $debt['summ']-$debt2['summ']);
                                }else{
                                                $this->reclaimDebt($debt['id'],
                                                "ring dependence with ".
                                                $debt2['id'],false);
                                        $this->modifyDebtSumm($debt2['id'],
                                                $debt2['summ']-$debt['summ']);
                                }
                        }
                }
              }
        }



}


?>

