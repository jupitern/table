<?php

require '../../vendor/autoload.php';

$table = \Jupitern\Table\Table::instance()
	->setData([
		['id' => 1, 'name' => 'Peter', 'age' => '35', 'phone' => '961 168 851'],
		['id' => 2, 'name' => 'John', 'age' => '44', 'phone' => '169 899 742'],
		['id' => 3, 'name' => 'Peter', 'age' => '22', 'phone' => '737 853 346'],
		['id' => 4, 'name' => 'Clark', 'age' => '34', 'phone' => '169 574 741'],
		['id' => 5, 'name' => 'Alex', 'age' => '65', 'phone' => '732 753 467'],
	])
	->attr('id', 'demoTable')
	->attr('class', 'table table-bordered table-striped table-hover')
	->attr('cellspacing', '0')
	->attr('width', '100%')
	->rowAttr('class', function($row) {
		return $row['age'] > 40 ? 'info' : '';
	})
	->rowAttr('data-id', function ($row) {
		return 'row-' . $row['id'];
	})
	->column()
		->title('Name')
		->value(function ($row) {
			return rand(1,10)%2 ? '<b>'.$row['name'].'</b>' : $row['name'];
		})
		->css('color', 'green')
		->css('width', '50%')
		->css('background-color', '#ccc', true)
	->add()
	->column()
		->title('Age')
		->value('age')
		->css('color', 'red')
		->css('width', '20%')
		->attr('data-age', function($row) {
			return $row['age'];
		})
		->css('color', function($row) {
			return $row['age'] > 40 ? '#00f' : 'inherit';
		})
	->add()
	->column('Phone')
		->value('phone')
		->css('color', 'red')
		->css('width', '20%')
	->add()
	->column()
		->value(function ($row) {
			return '<a href="country/'. $row['id'] .'">edit</a>';
		})
		->css('width', '10%')
	->add();
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
