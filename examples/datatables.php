<?php

require('db.php');
require('../vendor/autoload.php');

require 'header.php';
?>

<div class="container">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<h4>Datatables example:</h4> <br/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5 col-xs-12">
			<?php
			try {
				$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS,
					array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
				);
				$data = $db->query("SELECT * FROM countries limit 50")->fetchAll(PDO::FETCH_OBJ);
				$filterData = $db->query("SELECT country as val, country FROM countries limit 10")
								->fetchAll(PDO::FETCH_OBJ);
			}
			catch (\PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}

			try{
				\Jupitern\Table\Table::instance('dt_example1')
					->setData($data)
					->attr('class', 'table table-bordered table-striped table-hover')
					->attr('cellspacing', '0')
					->attr('width', '100%')
					->column()
						->title('Country')
						->value('country')
						->filter($filterData)
						->attr('data-val', 'foo')
						->css('width', '50%')
						->css('background-color', '#efefef')
						->css('background-color', '#f5f5f5', true)
					->add()
					->column()
						->title('Country Code')
						->value('country_code')
						->filter()
						->css('color', '#778899')
						->css('width', '20%')
					->add()
					->column()
						->title('Phone Code')
						->value('phone_code')
						->filter()
						->css('color', '#DEB887')
						->css('width', '20%')
					->add()
					->column()
						->value(function ($row) {
							return '<a href="#'.$row->id.'">edit</a>';
						})
						->css('width', '10%')
					->add()
					->plugin('Datatables')
						->param('columnDefs', '[{ "targets": 3, "orderable": false }]')
					->add()
					->render();
			}
			catch (\Exception $e) {
				echo 'Error: ' . $e->getMessage();
			}
			?>
		</div>
		<div class="col-md-7 col-xs-12">
			<pre><code class="php6" style="font-size: 11.5px;">
try {
	$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS,
		array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
	);
	$data = $db->query("SELECT * FROM countries limit 50")->fetchAll(PDO::FETCH_OBJ);
	$filterData = $db->query("SELECT country as val, country FROM countries limit 10")
					->fetchAll(PDO::FETCH_OBJ);
}
catch (\PDOException $e) {
	echo 'Error: ' . $e->getMessage();
}

try{
	\Jupitern\Table\Table::instance('dt_example1')
		->setData($data)
		->attr('class', 'table table-bordered table-striped table-hover')
		->attr('cellspacing', '0')
		->attr('width', '100%')
		->column()
			->title('Country')
			->value('country')
			->filter($filterData)
			->attr('data-val', 'foo')
			->css('width', '50%')
			->css('background-color', '#efefef')
			->css('background-color', '#f5f5f5', true)
		->add()
		->column()
			->title('Country Code')
			->value('country_code')
			->filter()
			->css('color', '#778899')
			->css('width', '20%')
		->add()
		->column()
			->title('Phone Code')
			->value('phone_code')
			->filter()
			->css('color', '#DEB887')
			->css('width', '20%')
		->add()
		->column()
			->value(function ($row) {
				return '&lt;a href="#'.$row->id.'"&gt;edit&lt;/a&gt;';
			})
			->css('width', '10%')
		->add()
		->plugin('Datatables')
			->param('columnDefs', '[{ "targets": 3, "orderable": false }]')
		->add()
		->render();
}
catch (\Exception $e) {
	echo 'Error: ' . $e->getMessage();
}
			</code></pre>
		</div>
	</div>
</div>

</body>
</html>