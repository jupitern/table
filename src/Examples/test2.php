<?php

require '../../vendor/autoload.php';

// grab data from db with PDO or in alternative from your framework ORM
$db = new PDO('mysql:host=HOST_NAME;dbname=DB_NAME;charset=utf8', 'DB_USERNAME', 'DB_PASSWORD',
	array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);
// data to populate table
$data = $db->query("SELECT id, name, age, phone FROM persons")->fetchAll(PDO::FETCH_OBJ);
// used for column filter
$filterData = $db->query("SELECT name as val, name FROM persons limit 10")->fetchAll(PDO::FETCH_OBJ);

\Jupitern\Table\Table::instance()
	->setData($data)
	->attr('table', 'id', 'demoTable')
	->attr('table', 'class', 'table table-bordered table-striped table-hover')
	->attr('table', 'cellspacing', '0')
	->attr('table', 'width', '100%')
	->column()
		->title('Name')
		->value(function ($row) {
			return rand(1,10)%2 ? '<b>'.$row['name'].'</b>' : $row['name'];
		})
		->filter($filterData)
		->css('td', 'color', 'green')
		->css('td', 'width', '50%')
		->css('td', 'background-color', '#ccc')
	->add()
	->column()
		->title('Age')
		->value('age')
		->filter()
		->css('td', 'color', 'red')
		->css('td', 'width', '20%')
	->add()
	->column('Phone')
		->filter()
		->value('phone')
		->css('td', 'color', 'red')
		->css('td', 'width', '20%')
	->add()
	->column()
		->value(function ($row) {
			return '<a href="country/'. $row['id'] .'">edit</a>';
		})
		->css('td', 'width', '10%')
	->add()
?>

<html>
<head>
	<!-- JQUERY -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

	<!-- DATATABLES -->
	<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

	<!-- Bootstrap and Datatables Bootstrap theme (OPTIONAL) -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script type="text/javascript">

		$(document).ready(function(){
			$('#demoTable').DataTable();
		});

	</script>
</head>
<body>
<div style="width: 50%; margin: 30px;">
	<?php $table->render(); ?>
</div>
</body>
</html>