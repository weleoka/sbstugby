<?php
/*
 * Class User
 *
 */
 class CUser{

 	public function __construct($db) {
 		$this->db=$db;
 	}


/*
 * Is Authenticated.
 *
 */
	public function IsAuthenticated() {
		if(isset($_SESSION['user'])){
		return true;
		} else {
		return false;
		}
	}


	public function statusIsAuthenticated() {
		$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

      if($acronym) {
      	$output = "Du är inloggad som: $acronym ({$_SESSION['user']->name})";
      	$output.= "<h1><a href='view.php'>Admin page</a></h1>";
      } else {
     		$output = "Du är inte inloggad.";
      }
      return $output;
	}


/*
 * login
 *
 */
	public function login($user,$password) {
		$sql = "SELECT acronym, name FROM User WHERE acronym = ? AND password = md5(concat(?, salt))";
		$params = array();
		$params=[htmlentities($user),  htmlentities($password)];
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);

		if(isset($res[0])) {
			$_SESSION['user'] = $res[0];
			return true;
		} else {
			return false;
		}
	}




/*
 * logout
 *
 */
	public function logout(){
		unset($_SESSION['user']);
	}

}
