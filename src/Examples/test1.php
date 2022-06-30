<?php

require '../Table/Properties.php';
require '../Table/TableColumn.php';
require '../Table/Table.php';

$table = \Jupitern\Table\Table::instance()
	->setData([
		['id' => 1, 'name' => 'Peter', 'age' => '35', 'phone' => '961 168 851'],
		['id' => 2, 'name' => 'John', 'age' => '44', 'phone' => '169 899 742'],
		['id' => 3, 'name' => 'Peter', 'age' => '22', 'phone' => '737 853 346'],
		['id' => 4, 'name' => 'Clark', 'age' => '34', 'phone' => '169 574 741'],
		['id' => 5, 'name' => 'Alex', 'age' => '65', 'phone' => '732 753 467'],
	])
//    ->attrs('table', ['class' => 'table table-bordered', 'cellspacing' => '0'])
	->attr('table', 'id', 'demoTable')
	->attr('table', 'class', 'table table-bordered table-striped table-hover')
	->attr('table', 'cellspacing', '0')
	->attr('table', 'width', '100%')
    ->attr('tr', 'data-text', 'bla bla bla bla bla')
    ->attr('tr', 'data-id', function($row) {
    	return 'row-' . $row['id'];
    })
    ->css('tr', 'background-color', '#aaa')
	->column()
		->title('Name')
		->value(function ($row) {
			return rand(1,10)%2 ? '<b>'.$row['name'].'</b>' : $row['name'];
		})
		->attr('td', 'data-text', 'bla bla bla')
		->css('th', 'color', 'green')
		->css('td', 'width', '50%')
		->css('td', 'background-color', '#ccc')
	->add()
	->column()
		->title('Age')
		->value('age')
		->css('th', 'color', 'red')
		->css('td', 'width', '20%')
	->add()
	->column()
		->value('phone')
		->css('td', 'color', 'red')
		->css('td', 'width', '20%')
	->add()
	->column()
		->value(function ($row) {
			return '<a href="country/'. $row['id'] .'">edit</a>';
		})
		->css('td', 'width', '10%')
	->add();
?>


<html>
	<head>
		<!-- JQUERY -->
        <script
                src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
                integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
                crossorigin="anonymous"></script>

		<!-- DATATABLES -->
		<link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
		<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

        <!-- Bootstrap and Datatables Bootstrap theme (OPTIONAL) -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


        <script type="text/javascript">

            $(document).ready( function () {
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
