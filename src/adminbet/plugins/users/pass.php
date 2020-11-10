<?php 


class pass {
	
	
public $strength = 8;
	
protected $saltLength = 22;

/**
 * Create a random string for a salt.
 *
 * @return string
 */
public function createSalt()
{
	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	return substr(str_shuffle(str_repeat($pool, 5)), 0, $this->saltLength);
}

 	
	public function hash ( $value ){
		$salt = $this->createSalt();
		
        return $salt.hash('sha256', $salt.$value);
	}
	
	public function slowEquals($a, $b)
    {
        $diff = strlen($a) ^ strlen($b);

        for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++) {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }

        return $diff === 0;
    }
	

	
	 public function check($value, $hashedValue)
    {
        $salt = substr($hashedValue, 0, $this->saltLength);

        return $this->slowEquals($salt.hash('sha256', $salt.$value), $hashedValue);
    }
	
	
}




?>