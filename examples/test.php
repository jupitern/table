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

	<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>

	<style type="text/css"> a { text-decoration: none; } </style>

</head>
<body>

<br clear="both"/><br clear="both"/>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2>PHP datatables</h2>
			<b>github:</b> <a href="https://github.com/jupitern/datatables">https://github.com/jupitern/datatables</a> <br/><br/>
			<p>
			agnostic framework wrapper for datatables (tested with v1.10.4) <br/>
			can be used for simple table generation without datatables <br/>
			can be easily integrated with any framework orm <br/>
			</p>
			<p>
				<a href="https://github.com/jupitern/datatables#datatables">Read Documentation</a>
			</p>
			<p><b>Author:</b> <a href="http://nunochaves.com">Nuno Chaves (JupiterN)</a></p>
		</div>
	</div>
	<br/><br/>
	<div class="row">
		<div class="col-md-5 col-xs-12">

			<h3>Datatables example:</h3>
			<?php
			require('db.php');
			require('../vendor/autoload.php');

			try {
				$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS,
					array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
				);
				$data = $db->query("SELECT * FROM countries limit 20")->fetchAll(PDO::FETCH_OBJ);
				$filterData = $db->query("SELECT country as val, country FROM countries limit 10")->fetchAll(PDO::FETCH_OBJ);

				\Jupitern\Datatables\Datatables::instance('dt_example')
					->setData($data)
					->jsParam('columnDefs', '[{ "targets": 3, "orderable": false }]')
					->attr('class', 'table table-bordered table-striped table-hover')
					->attr('cellspacing', '0')
					->attr('width', '100%')
					->column('Country')
						->value(function ($row) {
							return rand(1,10)%2 ? '<b>'.$row->country.'</b>' : $row->country;
						})
						->filter($filterData)
						->css('color', '#888')
						->css('width', '50%')
						->css('background-color', '#efefef')
						->css('background-color', '#f5f5f5', true)
					->add()
					->column('Country Code')
						->filter()
						->value('country_code')
						->css('color', '#778899')
						->css('width', '20%')
					->add()
					->column('Phone Code')
						->filter()
						->value('phone_code')
						->css('color', '#DEB887')
						->css('width', '20%')
					->add()
						->column('')->value(function ($row) {
							return '<a href="country/'.$row->id.'">edit</a>';
						})
						->css('width', '10%')
					->add()
					->render();
			}
			catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
			?>

		</div>
		<div class="col-md-7">
			<br/><br/>
			<pre class="prettyprint">

\Jupitern\Datatables\Datatables::instance('dt_example')
	->setData($data)
	->jsParam('columnDefs', '[{ "targets": 3, "orderable": false }]')
	->attr('class', 'table table-bordered table-striped table-hover')
	->attr('cellspacing', '0')
	->attr('width', '100%')
	->column('Country')
		->value(function ($row) {
			return rand(1,10)%2 ? '&lt;b&gt;'.$row->country.'&lt;/b&gt;' : $row->country;
		})
		->filter($filterData)
		->css('color', '#888')
		->css('width', '50%')
		->css('background-color', '#efefef')
		->css('background-color', '#f5f5f5', true)
	->add()
	->column('Country Code')
		->filter()
		->value('countryCode')
		->css('color', '#778899')
		->css('width', '20%')
	->add()
	->column('Phone Code')
		->filter()
		->value('phoneCode')
		->css('color', '#DEB887')
		->css('width', '20%')
	->add()
		->column('')->value(function ($row) {
			return '<a href="country/'.$row->idCountry.'">edit</a>';
		})
		->css('width', '10%')
	->add()
	->render();

			</pre>
		</div>
	</div>
	<br/><br/>
	<div class="row">
		<div class="col-md-5 col-xs-12">

			<h3>Simple table example:</h3>
			<?php

			try {
				\Jupitern\Datatables\Datatables::instance('dt_example2')
					->setData([
						['country' => 'Portugal', 'country_code' => '351', 'phone_code' => '351']
					])
					->attr('class', 'table table-bordered table-striped table-hover')
					->attr('cellspacing', '0')
					->attr('width', '100%')
					->column('Country')
						->value('country')
						->css('color', '#888')
						->css('width', '50%')
						->css('background-color', '#efefef')
						->css('background-color', '#f5f5f5', true)
					->add()
					->column('Country Code')
						->value('country_code')
						->css('color', '#778899')
						->css('width', '20%')
					->add()
					->column('Phone Code')
						->value('phone_code')
						->css('color', '#DEB887')
						->css('width', '20%')
					->add()
					->column('')->value(function ($row) {
						return '<a href="country/'.$row->id.'">edit</a>';
					})
						->css('width', '10%')
					->add()
					->disableJs()
					->render();
			}
			catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
			?>

		</div>
		<div class="col-md-7">
			<br/><br/>
			<pre class="prettyprint">

\Jupitern\Datatables\Datatables::instance('dt_example2')
	->setData($data)
	->attr('class', 'table table-bordered table-striped table-hover')
	->attr('cellspacing', '0')
	->attr('width', '100%')
	->column('Country')
		->value(function ($row) {
			return rand(1,10)%2 ? '&lt;b&gt;'.$row->country.'&lt;/b&gt;' : $row->country;
		})
		->css('color', '#888')
		->css('width', '50%')
		->css('background-color', '#efefef')
		->css('background-color', '#f5f5f5', true)
	->add()
	->column('Country Code')
		->value('countryCode')
		->css('color', '#778899')
		->css('width', '20%')
	->add()
	->column('Phone Code')
		->value('phoneCode')
		->css('color', '#DEB887')
		->css('width', '20%')
	->add()
	->column('')->value(function ($row) {
		return '<a href="country/'.$row->idCountry.'">edit</a>';
	})
		->css('width', '10%')
	->add()
	->disableJs()
	->render();
			</pre>
		</div>
	</div>

	<br/><br/>
	<div class="row">
		<div class="col-md-5 col-xs-12">

			<h3>Remote source example:</h3>
			<?php

			try {
				\Jupitern\Datatables\Datatables::instance('dt_example3')
					->setDataUrl('http://localhost:81/git_repos/datatables/examples/getRemoteData.php')
					->attr('class', 'table table-bordered table-striped table-hover')
					->attr('cellspacing', '0')
					->attr('width', '100%')
					->column('Country')
						->value('country')
						->css('color', 'red')
						->css('width', '50%')
						->css('background-color', '#efefef')
						->css('background-color', '#f5f5f5', true)
					->add()
					->column('Country Code')
						->value('country_code')
						->css('color', '#778899')
						->css('width', '20%')
					->add()
					->column('Phone Code')
						->value('phone_code')
						->css('color', '#DEB887')
						->css('width', '20%')
					->add()
//					->column('')
//						->value(function ($row) {
//							return '<a href="country/'.$row->id.'">edit</a>';
//						})
//						->css('width', '10%')
//					->add()
					->render();
			}
			catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
			?>

		</div>
		<div class="col-md-7">
			<br/><br/>
			<pre class="prettyprint">


			</pre>
		</div>
	</div>

</div>

</body>
</html>