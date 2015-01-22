<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<!-- JQUERY -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>

	<!-- BOOTSTRAP -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<!-- DATATABLES -->
	<!--<link href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" rel="stylesheet">-->
	<link href="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
	<script src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
	<script src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script>

</head>
<body>

<br clear="both"/><br clear="both"/>

<div class="container">
	<div class="row">
		<div class="col-md-12">

			<?php
			require('autoload.php');

			try {
				$db = new PDO('mysql:host=;dbname=;charset=utf8', '', '',
						array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
				);

				$data = $db->query("SELECT * FROM countries")->fetchAll(PDO::FETCH_OBJ);

				$filterData = $db->query("SELECT country as val, country FROM countries limit 10")->fetchAll(PDO::FETCH_OBJ);
			}
			catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}

			echo \Jupitern\Datatables\Datatables::instance('dt_example')
					->setData($data)
					->attr('class', 'table table-bordered table-striped table-hover')
					->attr('cellspacing', '0')
					->attr('width', '100%')
					->column('Country')
						->value(function ($row) {
							return rand(1,10)%2 ? '<b>'.$row->country.'</b>' : $row->country;
						})
						->filter($filterData)
						->css('color', 'green')
						->css('width', '50%')
						->css('background-color', '#ccc', true)
					->add()
					->column('Country Code')
						->filter()
						->value('countryCode')
						->css('color', 'red')
						->css('width', '20%')
					->add()
					->column('Phone Code')
						->filter()
						->value('phoneCode')
						->css('color', 'red')
						->css('width', '20%')
					->add()
					->column('')->value(function ($row) {
						return '<a href="country/'.$row->idCountry.'">edit</a>';
					})
					->css('width', '10%')
					->add()
					->render();
			?>

			<script type="text/javascript">

				$(document).ready(function() {

					var dt_example = $("#dt_example").DataTable({
						orderCellsTop: true,
						"sDom": '<"top">rt<"bottom"ip><"clear">',
						"columnDefs": [ { "targets": 3, "orderable": false } ]
					});

					// aply search to input and select fields
					$("#dt_example thead input, #dt_example thead select").on( 'blur change', function () {
						dt_example
							.column( $(this).parent().index()+':visible' )
							.search( this.value )
							.draw();
					});

				});

			</script>

		</div>
	</div>
</div>

</body>
</html>