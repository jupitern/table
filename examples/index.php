<?php

require('db.php');
require('../vendor/autoload.php');

try {
	$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS,
		array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
	);
	$data = $db->query("SELECT * FROM countries limit 30")->fetchAll(PDO::FETCH_OBJ);
	$filterData = $db->query("SELECT country as val, country FROM countries limit 10")->fetchAll(PDO::FETCH_OBJ);
}
catch (\PDOException $e) {
	echo 'Error: ' . $e->getMessage();
}
?>

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

<br clear="both"/>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2>jupitern/table</h2>
			<b>github:</b> <a href="https://github.com/jupitern/table">https://github.com/jupitern/datatables</a> <br/><br/>
			<p style="padding-left: 18px; line-height: 22px;">
				Build Html tables using php (objets, arrays) <br/>
				Pass your data using: <br/>
				&nbsp;&nbsp;&nbsp;- arrays (associative or not) <br/>
				&nbsp;&nbsp;&nbsp;- object collections (using PDO or you favourite framework ORM) <br/>
				Give some power to you tables with your preferred js library: <br/>
				&nbsp;&nbsp;&nbsp;- Datatables (tested with v1.10.4) <br/>
				&nbsp;&nbsp;&nbsp;- <i>more to come...</i> <br/><br/>

				<a href="https://github.com/jupitern/datatables#datatables">Read Documentation</a> <br/><br/>
				<b>author:</b> <a href="http://nunochaves.com">Nuno Chaves (JupiterN)</a>
			</p>

			<br clear="both" />
			<br clear="both" />

			<div role="tabpanel">

				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#array" aria-controls="array" role="tab" data-toggle="tab">Array</a></li>
					<li role="presentation"><a href="#arrayAssoc" aria-controls="arrayAssoc" role="tab" data-toggle="tab">Associative Array</a></li>
					<li role="presentation"><a href="#pdo" aria-controls="pdo" role="tab" data-toggle="tab">PDO</a></li>
					<li role="presentation"><a href="#orm" aria-controls="orm" role="tab" data-toggle="tab">ORM</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane" id="array">

						<br clear="both" />
						<div class="row">
							<div class="col-xs-5">
								<?php
								try{
									\Jupitern\Table\Table::instance('dt_example1')
										->setData([[1,"Afghanistan","AF","96"],[2,"Porugal","PT","351"]])
										->attr('class', 'table table-bordered table-striped table-hover')
										->attr('cellspacing', '0')
										->attr('width', '100%')
										->column()
											->title('Country')
											->filter('[[1,"Afghanistan","AF","96"],[2,"Porugal","PT","351"]]')
											->value(1)
											->attr('data-val', 'foo')
											->css('width', '50%')
											->css('background-color', '#efefef')
											->css('background-color', '#f5f5f5', true)
										->add()
										->column()
											->title('Country Code')
											->value(2)
											->filter()
											->css('color', '#778899')
											->css('width', '20%')
										->add()
										->column()
											->title('Phone Code')
											->value(3)
											->css('color', '#DEB887')
											->css('width', '20%')
										->add()
										->column()
											->value(function ($row) {
												return '<a href="#'.$row[0].'">edit</a>';
											})
											->css('width', '10%')
										->add()
										->render();
								}
								catch (\Exception $e) {
									echo 'Error: ' . $e->getMessage();
								}
								?>
							</div>
							<div class="col-xs-7">
								example:
							</div>
						</div>
					</div> <!-- tabpanel -->

					<div role="tabpanel" class="tab-pane" id="arrayAssoc">

						<br clear="both" />
						<div class="row">
							<div class="col-xs-5">
								<?php
								try{
									\Jupitern\Table\Table::instance('dt_example2')
										->setData([
											['id' => 1, 'country' => 'Afghanistan', 'country_code' => 'AF', 'phone_code' => '96'],
											['id' => 2, 'country' => 'Porugal', 'country_code' => 'PT', 'phone_code' => '351'],
										])
										->attr('class', 'table table-bordered table-striped table-hover')
										->attr('cellspacing', '0')
										->attr('width', '100%')
										->column()
										->title('Country')
										->css('color', '#888')
											->value('country')
											->css('width', '50%')
											->css('background-color', '#efefef')
											->css('background-color', '#f5f5f5', true)
										->add()
										->column()
											->title('Country Code')
											->value('country_code')
											->css('color', '#778899')
											->css('width', '20%')
										->add()
										->column()
											->title('Phone Code')
											->value('phone_code')
											->css('color', '#DEB887')
											->css('width', '20%')
										->add()
										->column()
											->value(function ($row) {
												return '<a href="#'.$row['id'].'">edit</a>';
											})
											->css('width', '10%')
										->add()
										->render();
								}
								catch (\Exception $e) {
									echo 'Error: ' . $e->getMessage();
								}
								?>
							</div>
							<div class="col-xs-7">
								example:
							</div>
						</div>
					</div> <!-- tabpanel -->

				</div> <!-- TabPanes -->

			</div>

		</div>
	</div>
	<br/><br/>
	<div class="row">
		<div class="col-md-5 col-xs-12">


			<?php

			try {
//				\Jupitern\Table\Table::instance('dt_example')
//					->setData($data)
//					->setData([
//						['id' => 1, 'country' => 'Afghanistan', 'country_code' => 'AF', 'phone_code' => '96'],
//						['id' => 2, 'country' => 'Porugal', 'country_code' => 'PT', 'phone_code' => '351'],
//					])
//					->attr('class', 'table table-bordered table-striped table-hover')
//					->attr('cellspacing', '0')
//					->attr('width', '100%')
//					->column()
//						->title('Country')
//						->value('country')
//						->value(function ($row) {
//							return rand(1,10)%2 ? '<b>'.$row->country.'</b>' : $row->country;
//						})
//						->filter($filterData)
//						->filter([['0', 'AGF'], ['1', 'PT']])
//						->css('color', '#888')
//						->css('width', '50%')
//						->css('background-color', '#efefef')
//						->css('background-color', '#f5f5f5', true)
//					->add()
//					->column()
//						->title('Country Code')
//						->filter()
//						->value('country_code')
//						->css('color', '#778899')
//						->css('width', '20%')
//					->add()
//					->column()
//						->title('Phone Code')
//						->filter()
//						->value('phone_code')
//						->css('color', '#DEB887')
//						->css('width', '20%')
//					->add()
//					->column()
//						->value(function ($row) {
//							return '<a href="country/'.$row->id.'">edit</a>';
//						})
//						->value('url')
//						->css('width', '10%')
//					->add()
//					->plugin('Datatables')
//						->ajax('http://localhost:81/git_repos/datatables/examples/getRemoteData.php')
//						->param('columnDefs', '[{ "targets": 3, "orderable": false }]')
//					->add()
//					->render();
			}
			catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
			?>

		</div>
	</div>
</div>

</body>
</html>