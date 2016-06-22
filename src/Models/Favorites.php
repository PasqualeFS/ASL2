<?php
	
	namespace App\Models;

	use Silex\Application;

class Favorites {
	// need an add function that takes user ID and recipe ID and inserts it into the favorites table.
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
	// add delete/remove function for favorites




}