<?php
require __DIR__ .'/../libraries/PasswordHash.php';
include(__DIR__ .'/../funciones.php');
class Usuario {
    private $pass;
	private $hash;
    private $user;
	private $hasher; 
	private $nombre;
private $email;
private $dir;
private $city;
private $reg;
private $edad;	
private $db;

 public function __construct()
 
  {
	  include(__DIR__ .'/../config.php');
	  $this->db = $db;
      $this->hasher = new PasswordHash(8, false);
  }



    public function create_user($user, $email, $pass,$nombre,$edad, $dir, $city, $reg) {
		$db = $this->db;
		$hash = $this->hasher->HashPassword($pass);
if (strlen($hash) < 20)
	echo('Failed to hash new password');
unset($hasher);
     ($stmt = $db->prepare('insert into usuario values (?,?,?,?,?,?,?,?)'))
	|| fail('MySQL prepare', $db->error);
$stmt->bind_param('ssssisss', $user, $email, $hash,$nombre,$edad, $dir, $city, $reg)
	|| fail('MySQL bind_param', $db->error);	
	if (!$stmt->execute()) {
	$save_error = $db->error;
	$stmt->close();

// Does the user already exist?
	($stmt = $db->prepare('select ID_user from usuario where ID_user=?'))
		|| fail('MySQL prepare', $db->error);
	$stmt->bind_param('s', $user)
		|| fail('MySQL bind_param', $db->error);
	$stmt->execute()
		|| fail('MySQL execute', $db->error);
	$stmt->store_result()
		|| fail('MySQL store_result', $db->error);

	if ($stmt->num_rows === 1)
		fail('This username is already taken');
	else
		fail('MySQL execute', $save_error);
}
	
echo("Usuario creado\n");
    }

    public function delete($user) {
			$db = $this->db;
      ($stmt = $db->prepare('select ID_user from usuario where ID_user=?'))
		|| fail('MySQL prepare', $db->error);
	$stmt->bind_param('s', $user)
		|| fail('MySQL bind_param', $db->error);
	$stmt->execute()
		|| fail('MySQL execute', $db->error);
	$stmt->store_result()
		|| fail('MySQL store_result', $db->error);

	if ($stmt->num_rows === 1){
		$stmt->close();
	($stmt = $db->prepare('DELETE from usuario where ID_user=?'))
	|| fail('MySQL prepare', $db->error);
	$stmt->bind_param('s', $user)
		|| fail('MySQL bind_param', $db->error);
		if (!$stmt->execute()) {
			return 2;
		}else{
		return 0;
		}
	}else{
		return 1;
	}}
    
	
	public function val_log($user, $pass){
		$db = $this->db;
			$hash = '*'; // In case the user is not found
	($stmt = $db->prepare('select Contra_user from usuario where ID_user=?'))
		|| fail('MySQL prepare', $db->error);
	$stmt->bind_param('s', $user)
		|| fail('MySQL bind_param', $db->error);
	$stmt->execute()
		|| fail('MySQL execute', $db->error);
	$stmt->bind_result($hash)
		|| fail('MySQL bind_result', $db->error);
	if (!$stmt->fetch() && $db->errno)
		fail('MySQL fetch', $db->error);

	if ($this->hasher->CheckPassword($pass, $hash)) {
		return true;
	} else {
		return false;
		
	}
	}

    //fields getters and setters here as needed

}
?>