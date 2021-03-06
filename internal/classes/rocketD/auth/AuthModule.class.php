<?php
namespace rocketD\auth;
abstract class AuthModule extends \rocketD\db\dbEnabled
{
	protected $internalUser;
	// TODO: Move reset to internal authmod
	const COL_PW_RESET_KEY = 'ResetPasswordKey';
	const COL_PW_RESET_DATE = 'resetPasswordDate';
	const CAN_CHANGE_PW = false; // override this!

	public static $AUTH_MOD_NAME = 'rocketD\auth\AuthModule';

	abstract public function authenticate($requestVars);
	abstract public function isPasswordCurrent($userID);
	abstract public function dbSetPassword($userID, $newPassword);
	abstract protected function addRecord($userID, $userName, $password);
	abstract public function updateRecord($userID, $username, $password);
	abstract public function verifyPassword($user, $password);
	abstract public function requestPasswordReset($username, $email, $returnURL);
	abstract protected function sendPasswordResetEmail($sendTo, $returnURL, $resetKey);
	abstract public function changePasswordWithKey($username, $key, $newpass);
	abstract public function syncExternalUser($userName);

	/**
	 * Fetch the Obojobo user data by it's ID
	 *
	 * @return \rocketD\auth\User on success, false on failure
	 * @author /bin/bash: niutil: command not found
	 **/
	public function fetchUserByID($userID)
	{
		if(!is_numeric($userID) || $userID < 1)
		{
			trace('userID not valid', true);
			return false;
		}

		$this->defaultDBM();
		//Fetch user data
		$qstr = "SELECT * FROM  ".\cfg_core_User::TABLE." WHERE ".\cfg_core_User::ID."='?' and ".\cfg_core_User::AUTH_MODULE." = '?' ";
		$q = $this->DBM->querySafe($qstr ,$userID, static::$AUTH_MOD_NAME);
		$return = $this->buildUserFromQueryResult($this->DBM->fetch_obj($q));

		return $return;
	}


	public function fetchUserByLogin($login)
	{
		// begin authentication, lookup user id by username
		$userID = $this->getUIDforUsername($login);
		if ($userID)
		{
			return $this->fetchUserByID($userID);
		}
		return false;
	}

	// TODO: this needs to be the one function for this call, limitations in php 5.2 required the authmods to have their own copy of this function for the retrieving the constants
	public function getAllUsers()
	{
		$this->defaultDBM();
		$users = array();
		$q = $this->DBM->query("SELECT ". \cfg_core_User::ID . " FROM ".\cfg_core_User::TABLE." WHERE ".\cfg_core_User::AUTH_MODULE." = '".static::$AUTH_MOD_NAME."'");
		while($r = $this->DBM->fetch_obj($q))
		{
			if($newUser = $this->fetchUserByID($r->{\cfg_core_User::ID}))
			{
				$users[] = $newUser;
			}
		}
		return $users;
	}

	// TODO: add password current info to user so that we can use memcache to determine if password is current
	protected function buildUserFromQueryResult($r)
	{
		if($r)
		{
			return new \rocketD\auth\User($r->{\cfg_core_User::ID}, $r->{\cfg_core_User::LOGIN}, $r->{\cfg_core_User::FIRST}, $r->{\cfg_core_User::LAST},
				$r->{\cfg_core_User::MIDDLE}, $r->{\cfg_core_User::EMAIL}, $r->{\cfg_core_User::CREATED_TIME}, $r->{\cfg_core_User::LOGIN_TIME});
		}
		return false;
	}

	public function getUser()
	{
		return $this->internalUser;
	}

	public function getUIDforUsername($username)
	{
		if($this->validateUsername($username) === true)
		{
			$this->defaultDBM();

			if($userID = \rocketD\util\Cache::getInstance()->getUIDForUserName($username))
			{
				return $userID;
			}

			if(!$this->DBM->connected)
			{
				trace('not connected', true);
				return false;
			}

			$q = $this->DBM->querySafe("SELECT ".\cfg_core_User::ID." FROM " . \cfg_core_User::TABLE . " WHERE ". \cfg_core_User::LOGIN . "='?' AND ". \cfg_core_User::AUTH_MODULE." = '?' LIMIT 1", $username, static::$AUTH_MOD_NAME);
			if($r = $this->DBM->fetch_obj($q))
			{
				// store in memcache
				\rocketD\util\Cache::getInstance()->setUIDForUserName($username, $r->{\cfg_core_User::ID});
				return $r->{\cfg_core_User::ID}; // return found user id
			}
		}
		return false;
	}

	public function createNewUser($userName, $fName, $lName, $mName, $email, $optionalVars=0)
	{
		// Only update if valid (empty keeps existing value)
		if($this->validateFirstName($fName) && $this->validateLastName($lName) && $this->validateMiddleName($mName) && $this->validateEmail($email))
		{
			// Invalidating memcache that has a list of all users
			// TODO: may be better to just append to the list then delete it

			\rocketD\util\Cache::getInstance()->clearAllUsers();

			$this->defaultDBM();
			$qstr = "INSERT INTO ".\cfg_core_User::TABLE."
			 SET ".\cfg_core_User::FIRST."='?',
			 ".\cfg_core_User::LAST."='?',
			 ".\cfg_core_User::MIDDLE."='?',
			 ".\cfg_core_User::EMAIL."='?',
			 ".\cfg_core_User::CREATED_TIME."=UNIX_TIMESTAMP(),
			 ".\cfg_core_User::LOGIN_TIME."=''";

			if($this->DBM->querySafe($qstr, $fName, $lName, $mName,  $email ))
			{
				return array('success' => true, 'userID' => $this->DBM->insertID);
			}
		}
		trace("cannot create user ", true);
		return array('success' => false, 'error' => 'Unable to create User.');
	}

	public function updateUser($userID, $userName, $fName, $lName, $mName, $email, $optionalVars=0)
	{
		// require a valid UID
		if($this->validateUID($userID))
		{
			// get a user from the db to get the current values;
			$user = $this->fetchUserByID($userID);
			if($user)
			{
				// Only update if valid (empty keeps existing value)
				if(!$this->validateFirstName($fName)) $fName = $user->first;
				if(!$this->validateLastName($lName)) $lName = $user->last;
				if(!$this->validateMiddleName($mName)) $mName = $user->mi;
				if(!$this->validateEmail($email)) $email = $user->email;

				$this->defaultDBM();
				$qstr = "UPDATE ".\cfg_core_User::TABLE."
				SET ".\cfg_core_User::FIRST."='?',
				 ".\cfg_core_User::LAST."='?',
				 ".\cfg_core_User::MIDDLE."='?',
				 ".\cfg_core_User::EMAIL."='?' WHERE ".\cfg_core_User::ID."='?' LIMIT 1";
				if($q = $this->DBM->querySafe($qstr, $fName, $lName, $mName, $email, $userID))
				{
					\rocketD\util\Cache::getInstance()->clearUserByID($userID);
					return array('success' => true, 'userID' => $userID);
				}
				else
				{
					trace("unable to update user " . $this->DBM->error(), true);
				}
			}
		}
		return array('success' => false, 'error' => 'Unable to update User.');
	}

	/**
	 * Not meant to be extended, this function will create a session with appropriate variables and update the database.
	 **/
	protected function storeLogin($user)
	{
		if ( ! ($user instanceof \rocketD\auth\User) || ! $this->validateUID($user->userID))
		{
			trace('userID not valid', true);
			return;
		}

		if ( ! session_id())
		{
			session_name(\AppCfg::SESSION_NAME);
			session_start();
		}

		// bypass issue in php 7.0.x https://github.com/php/php-src/pull/1739
		if(version_compare(PHP_VERSION, '7.0.0') < 0 || version_compare(PHP_VERSION, '7.1.0') >= 0){
			session_regenerate_id(false);
		}

		$_SESSION = array();// force a fresh start on the session variables
		$_SESSION['userID'] = $user->userID;
		$_SESSION['passed'] = true;
		$_SESSION['timestamp'] = time() + \AppCfg::AUTH_TIMEOUT;
		$this->internalUser = $user;

		$this->defaultDBM();
		$this->DBM->querySafe("UPDATE ".\cfg_core_User::TABLE." SET ".\cfg_core_User::SID." = '".session_id()."',  ".\cfg_core_User::LOGIN_TIME." = UNIX_TIMESTAMP() WHERE ".\cfg_core_User::ID."='?' LIMIT 1", $user->userID);
	}

	public function recordExistsForID($userID=0)
	{
		if(!$this->validateUID($userID)) return false;
		$this->defaultDBM();
		$q = $this->DBM->querySafe("SELECT * FROM ". \cfg_core_User::TABLE ." WHERE ". \cfg_core_User::ID ."='?' AND ".\cfg_core_User::AUTH_MODULE." = '?'", $userID, static::$AUTH_MOD_NAME);
		return $this->DBM->fetch_num($q) > 0;
	}

	protected function validateUID($userID)
	{;
		return \obo\util\Validator::isPosInt($userID);
	}

	public function validateUsername($username)
	{
		return true;
	}

	protected function validateFirstName($name)
	{
		return true;
	}

	protected function validateLastName($name)
	{
		return true;
	}

	protected function validateMiddleName($name)
	{
		return true;
	}

	protected function validateEmail($email)
	{
		return true;
	}

	protected function validateResetURL($URL)
	{
		return true;
	}

	public function removeRecord($userID)
	{
		$userGone = false;
		if($this->validateUID($userID))
		{
			$this->defaultDBM();
			$userGone = $this->DBM->querySafe("DELETE FROM ".\cfg_core_User::TABLE." WHERE ".\cfg_core_User::ID."='?' LIMIT 1", $userID);
			if ($userGone)
			{
				$this->DBM->querySafe("DELETE FROM obo_user_meta WHERE userID = '?'", $userID);
				$PM = \obo\perms\PermissionsManager::getInstance();
				$PM->removeAllPermsForUser($userID);
				\rocketD\util\Cache::getInstance()->delete('\rocketD\auth\AuthModule:fetchUserByID:'.$userID);
			}
		}
		return $userGone;
	}

	protected function getMetaField($userID, $key)
	{
		$this->defaultDBM();
		$qstr = "SELECT value FROM obo_user_meta WHERE ".\cfg_core_User::ID." = '?' AND meta = '?';";
		$result = $this->DBM->querySafe($qstr, $userID, $key);
		$fetched = $this->DBM->fetch_obj($result);

		if(!$fetched || !isset($fetched->value))
		{
			return false;
		}

		return $fetched->value;
	}

	protected function setMetaField($userID, $key, $value)
	{
		$this->defaultDBM();
		$qstr = "INSERT INTO obo_user_meta SET userID = '?', meta='?', value = '?' ON DUPLICATE KEY UPDATE value='?'";
		return $this->DBM->querySafe($qstr, $userID, $key, $value, $value);
	}

	public function getUserName($userID)
	{
		//use fetchUserBYID if memcahe is on
		if($user = \rocketD\util\Cache::getInstance()->getUserByID($userID))
		{
			return $user->login;
		}

		$q = $this->DBM->querySafe("SELECT ".\cfg_core_User::LOGIN." FROM ".\cfg_core_User::TABLE." WHERE ".\cfg_core_User::ID." = '?' LIMIT 1", $userID);
		if($r = $this->DBM->fetch_obj($q))
		{
			return $r->{\cfg_core_User::LOGIN};
		}
		return false;
	}

	protected function createSalt()
	{
		// return md5(uniqid(rand(), true));
		return md5(openssl_random_pseudo_bytes(50));
	}

	protected function makeResetKey()
	{
		return sha1(microtime(true));
	}

}
