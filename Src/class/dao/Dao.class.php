<?php
	class Dao{
		static protected $pdo = NULL;
		
		public function __construct(){
			// データベース接続
			if(is_null(self::$pdo)){
				self::connect();
			}
		}
		
		public function lastInsertId(){
			return self::$pdo->lastInsertId();
		}
		
		static public function connect(){
			try{
				self::$pdo = new PDO(_DSN, _USER, _PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`",PDO::ATTR_PERSISTENT => TRUE));
			}catch(PDOException $e){
				print('Connection failed:'.$e->getMessage());
				die();
			}
		}
		
		static function close(){
			self::$pdo = NULL;
		}
	}
?>