<?php
namespace App\Models;

class Favorites {
	private $db;

	public function __construct ($db) {
		$this->db = $db;
	}

	public function fetch ($user_id, $recipe_id = null) {
		$sql = "SELECT r.id, r.title FROM favorites f JOIN recipes r ON f.recipeid = r.id WHERE f.userid = :user_id";

		$values = array(':user_id' => $user_id);

		if ($recipe_id) {
			$sql .= " AND f.recipeid = :recipe_id";

			$values[':recipe_id'] = $recipe_id;
		}

		return $this->db->fetchAll($sql, $values);
	}

	public function add ($user_id, $recipe_id){
		$stmt=$this->db->prepare("INSERT INTO favorites (userid, recipeid, favorited_datetime) VALUES(:user_id, :recipe_id, NOW())");

		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':recipe_id', $recipe_id);

		return $stmt->execute();
	}

	public function delete ($recipe_id, $user_id = null) {
		$sql = "DELETE FROM favorites WHERE recipeid = :recipe_id";

		$values = array(':recipe_id' => $recipe_id);

		if ($user_id) {
			$sql .= " AND userid = :user_id";

			$values[':user_id'] = $user_id;
		}

		$sqlquery = $this->db->prepare($sql);
		return $sqlquery->execute($values);

	}

}