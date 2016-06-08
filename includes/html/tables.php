<?php 
defined('APP') or die;

/**
 * Affiche un tableau php sous forme de table html
 * @param $array
 * @return html
 */

function getHtmlTable($array) 
{
	$table = "<table>";
	foreach ($array as $item)
	{	
		$table.= "<tr>";
		foreach ($item as $value) {
			$table.= "<td>".$value."</td>";
		}
		$table.= "</tr>";		
	}
	$table.= "</table>";
	return $table;
}