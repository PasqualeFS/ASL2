<?php

namespace App\Controllers;

class Recipes {
	public function list(){
		$sqlquery = $dbh->prepare("SELECT id, title, ingredients, instructions FROM recipes");
		$sqlquery->execute();
		$result = $sqlquery->fetchAll();

		$recipeLink = "";

		foreach ($result as $row) {
	        $recipeLink .= '<a href="/details/' . $row['id'] . '">' . $row['title'] . '</a><br />';
	    }

	    return $recipeLink;

	}
}

