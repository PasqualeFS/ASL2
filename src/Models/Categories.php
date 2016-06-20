<?php

namespace App\Models;

use Silex\Application;

class Categories {
	private $db;

	public function __construct ($db) {
		$this->db = $db;
	}

	public function fetch (){
		$sqlquery = $this->db->prepare("SELECT id, category FROM categories");
		$sqlquery->execute();
		$result = $sqlquery->fetchAll(\PDO::FETCH_ASSOC);

		return $result;

	}
}