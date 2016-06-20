<?php

namespace App\Models;

use Silex\Application;

class Cuisine {
	private $db;

	public function __construct ($db) {
		$this->db = $db;
	}

	public function fetch (){
		$sqlquery = $this->db->prepare("SELECT id, type FROM cuisine");
		$sqlquery->execute();
		$result = $sqlquery->fetchAll(\PDO::FETCH_ASSOC);

		return $result;

	}
}