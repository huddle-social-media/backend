<?php

namespace Config;

class Database {
	// Static Class DB Connection Variables (for write and read)
	private static $DBConnection;

	public static function getDBConnection() {
		if(self::$DBConnection === null) {
				self::$DBConnection = new \PDO('mysql:host=localhost;dbname=huddle_test', 'root', '');
				self::$DBConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				self::$DBConnection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		}
		return self::$DBConnection;
	}

}