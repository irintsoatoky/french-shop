<?php

namespace App\Classe;

use App\Entity\Category;

// Création manuelle de cette class... => Il faut créer SearchType.php dans Form
class Search 
{
	/**
	 * @var string
	 */
	public $string = "";

	/**
	 * @var Category[]
	 */
	public $categories = [];
}
?>