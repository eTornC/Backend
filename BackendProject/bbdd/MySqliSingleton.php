<?php

namespace eTorn\Bbdd;

use eTorn\Constants\ConstantsDB;

class MySqliSingleton 
{
	private static $mySqliInstance = null;

	public static function getMySqliInstance()
	{
		if (self::$mySqliInstance == null) {
			
			self::$mySqliInstance = new \MySqli(ConstantsDB::DB_SERVER, 
												ConstantsDB::DB_USER, 
												ConstantsDB::DB_PASSWD, 
												ConstantsDB::DB_NAME	);

			self::$mySqliInstance->set_charset(ConstantsDB::DB_CHARSET);
		}

		return self::$mySqliInstance;
	}

}