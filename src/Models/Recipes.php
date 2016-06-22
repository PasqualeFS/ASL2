<?php

namespace App\Models;

use Silex\Application;

class Recipes {
	private $db;

	public function __construct ($db) {
		$this->db = $db;
	}

	public function fetch ($userId = null, $title = null, $category = null, $cuisine = null){
		$query = new \Doctrine\DBAL\Query\QueryBuilder($this->db);

		$query->select('id', 'title', 'ingredients', 'instructions')
			->from('recipes');

		$values = array();

		if ($userId) {
			$query->where('user_id = :user_id');

			$values[':user_id'] = $userId;
		}

		if ($title) {
			$query->where('LOWER(title) LIKE :title');

			$values[':title'] = '%' . strtolower($title) . '%';
		}

		if ($category) {
			$query->where('category_id = :category_id');

			$values[':category_id'] = $category;
		}

		if ($cuisine) {
			$query->where('cuisine_id = :cuisine_id');

			$values[':cuisine_id'] = $cuisine;
		}

		return $query->setParameters($values)->execute()->fetchAll();

	}

	public function fetchOne ($id){
		$sqlquery = $this->db->prepare("SELECT r.id, title, ingredients, instructions, category_id, cuisine_id, category, type FROM recipes r LEFT JOIN categories cat ON r.category_id = cat.id LEFT JOIN cuisine cu ON r.cuisine_id = cu.id WHERE r.id = :id");
		$sqlquery->bindParam(':id', $id);
		$sqlquery->execute();
		$result = $sqlquery->fetch();

		return $result;
	}

	public function insert ($title, $ingredients, $instructions, $category_id, $cuisine_id, $user_id){
		$stmt=$this->db->prepare("INSERT INTO recipes (title, ingredients, instructions, category_id, cuisine_id, user_id) VALUES (:title, :ingredients, :instructions, :category_id, :cuisine_id, :user_id)");

		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':ingredients', $ingredients);
		$stmt->bindParam(':instructions', $instructions);
		$stmt->bindParam(':category_id', $category_id);
		$stmt->bindParam(':cuisine_id', $cuisine_id);
		$stmt->bindParam(':user_id', $user_id);

		return $stmt->execute();
	}

	public function update ($id, $title, $ingredients, $instructions, $category_id, $cuisine_id){
		$stmt=$this->db->prepare("UPDATE recipes SET title = :title, ingredients = :ingredients, instructions = :instructions, category_id = :category_id, cuisine_id = :cuisine_id WHERE id = :id");

		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':ingredients', $ingredients);
		$stmt->bindParam(':instructions', $instructions);
		$stmt->bindParam(':category_id', $category_id);
		$stmt->bindParam(':cuisine_id', $cuisine_id);
		$stmt->bindParam(':id', $id);

		return $stmt->execute();
	}

	public function delete ($id) {
		$favoriteModel = new Favorites($this->db);

		$favoriteModel->delete($id);

		$stmt=$this->db->prepare("DELETE FROM recipes WHERE id = :id");
		$stmt->bindParam(':id', $id);

		return $stmt->execute();
	}
}