<?php

require('db.php');
require('../vendor/autoload.php');

require 'header.php';
?>

<div class="container">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<h4>Associtive array example:</h4> <br/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5 col-xs-12">
			<?php
			try{
				\Jupitern\Table\Table::instance('dt_example1')
					->setData([
						['id' => 1, 'country' => 'Afghanistan', 'country_code' => 'AF', 'phone_code' => '96'],
						['id' => 2, 'country' => 'Porugal', 'country_code' => 'PT', 'phone_code' => '351'],
					])
					->attr('class', 'table table-bordered table-striped table-hover')
					->attr('cellspacing', '0')
					->attr('width', '100%')
					->column()
						->title('Country')
						->value('country')
						->attr('data-val', 'foo')
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
		<div class="col-md-7 col-xs-12">
			<pre><code class="php6" style="font-size: 11.5px;">
$data = [
	['id' => 1, 'country' => 'Afghanistan', 'country_code' => 'AF', 'phone_code' => '96'],
	['id' => 2, 'country' => 'Porugal', 'country_code' => 'PT', 'phone_code' => '351'],
];

\Jupitern\Table\Table::instance('dt_example1')
	->setData($data)
	->attr('class', 'table table-bordered table-striped table-hover')
	->attr('cellspacing', '0')
	->attr('width', '100%')
	->column()
		->title('Country')
		->value('country')
		->attr('data-val', 'foo')
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
			return '&lt;a href="#'.$row['id'].'"&gt;edit&lt;/a&gt;';
		})
		->css('width', '10%')
	->add()
	->render();
			</code></pre>
		</div>
	</div>
</div>

</body>
</html>