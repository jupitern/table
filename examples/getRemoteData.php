<?php

try {
	require 'db.php';

	$start = $_POST['start'];
	$length = $_POST['length'];

	$orderBy = "order by ";
	foreach ($_POST['order'] as $orderField) {
		$orderBy .= $_POST['columns'][$orderField['column']]['data'] .' '. $orderField['dir'] .' ';
	}

	$query = "SELECT country, country_code, phone_code, 'cenas' as url
			  FROM countries $orderBy limit $start, $length";

	$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS,
		array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
	);
	$res = $db->query($query)->fetchAll(PDO::FETCH_OBJ);
	echo json_encode([
		"draw" => $_POST['draw'],
		"recordsTotal" => 242,
		"recordsFiltered" => 242,
		"data" => $res
	]);
}
catch (PDOException $e) {
	echo 'Error: '.$e->getMessage().'<br/>';
}
catch (Exception $e) {
	echo 'Error: '.$e->getMessage().'<br/>';
}