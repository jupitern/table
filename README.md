[![Build Status](https://scrutinizer-ci.com/g/jupitern/table/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jupitern/table/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jupitern/table/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jupitern/table/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/jupitern/datatables/v/stable.svg)](https://packagist.org/packages/jupitern/datatables) [![Total Downloads](https://poser.pugx.org/jupitern/datatables/downloads.svg)](https://packagist.org/packages/jupitern/datatables) [![Latest Unstable Version](https://poser.pugx.org/jupitern/datatables/v/unstable.svg)](https://packagist.org/packages/jupitern/datatables) [![License](https://poser.pugx.org/jupitern/datatables/license.svg)](https://packagist.org/packages/jupitern/datatables)
# jupitern/table
#### php table generation. integrates with your favourite orm and js library

Pass your data using:
* arrays (associative or not).
* result set using PDO or you favourite framework ORM.
* ajax source.

Give some power to you tables with your preferred js library:
* Datatables (tested with v1.10.4).
* more to come...
* easily extensible to add your custom plugin render

## Demo:

comming soon

## Requirements

PHP 5.4 or higher.

## Installation

Include jupitern/datatables in your project, by adding it to your composer.json file.
```javascript
{
    "require": {
        "jupitern/datatables": "0.*"
    }
}
```

## Usage
```php
// instance Datatables with instance name
\Jupitern\Table\Table::instance('dt_example')

// set data for non ajax requests
// using a array
->setData([ [1, 'Afghanistan', 'AF', '96'], [2, 'Porugal', 'PT', '351'] ])
// using a associative array
->setData([
	['id' => 1, 'country' => 'Afghanistan', 'country_code' => 'AF', 'phone_code' => '96'],
	['id' => 2, 'country' => 'Porugal', 'country_code' => 'PT', 'phone_code' => '351'],
])
// using PDO result or your framework ORM. see example how to grab $data at the end
->setData($data)

// add attributes to the <table> html tag one by one
->attr('class', 'table table-bordered table-striped table-hover')
->attr('cellspacing', '0')

// or add all <table> attributes at once
->attrs(['class' => 'table table-bordered', 'cellspacing' => '0']);

// add a new column for array data
->column()
	->title('Country')
	->value(1)
->add()

// add a new column for (associtive array, PDO or ORM) data
->column()
	->title('Country')
	->value('country')
->add()

// add a column with a closure for value field to process data in execution
// this example assumes data as object
->column()
	->title('Country')
	->value(function ($row) {
		return rand(1,10)%2 ? '<b>'.$row->country.'</b>' : $row->country;
	})
->add()

// onether closure example for adding a column with edit action with no title on <th>
// this example assumes data associative array
->column()
	->value(function ($row) {
		return '<a href="edit/'.$row['id'].'">edit '.$row['country'].'</a>';
	})
->add()

// add a column with text field as filter
->column()
	->title('Country')
	->value('country')
	->filter()
->add()

// add a column with a drop down field as filter
// $filterData as array
->column()
	->title('Country')
	->filter([[1, 'Afghanistan'], [2, 'Porugal']])
	->value('db_column_name')
->add()

// add a column with a drop down field as filter
// $filterData from (associtive array, PDO or ORM). see example how to grab $data at the end
->column()
	->title('Country')
	->filter($filterData)
	->value('db_column_name')
->add()

// add a column with some attributes and css for <th> and <td>
->column()
	->title('Country')
	->value('country')
	->attr('data-val', 'foo')					// add attributes to <th>
    ->css('background-color', '#f5f5f5', true)	// add css to <th>
    ->attr('data-val', 'bar')					// add attributes to <td>
    ->css('background-color', '#f5f5f5')		// add css to <td>
->add()

// add datatables plugin with some params to your table to
// get some paging ordering and filtering to work
->plugin('Datatables')
	// add this param to grab your data from ajax
	// this option sets several datatable params at once behind the scenes
	->ajax('http://localhost:81/git_repos/datatables/examples/getRemoteData.php')
	// add param disable ordering on actions column
	// any datatables params can be added using this function
	->param('columnDefs', '[{ "targets": 3, "orderable": false }]')
->add()

// echo table output
->render();

// OR return table output
->render(true);

```


## Examples
```php
// grab data from db with PDO or in alternative from your framework ORM
$db = new PDO('mysql:host=HOST_NAME;dbname=DB_NAME;charset=utf8', 'DB_USERNAME', 'DB_PASSWORD',
		array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);
// data to populate table
$data = $db->query("SELECT id, country, country_code, phone_code FROM countries")->fetchAll(PDO::FETCH_OBJ);
// used for column filter
$filterData = $db->query("SELECT country as val, country FROM countries limit 10")->fetchAll(PDO::FETCH_OBJ);

\Jupitern\Datatables\Datatables::instance('dt_example')
	->setData($data)
	->attr('class', 'table table-bordered table-striped table-hover')
	->attr('cellspacing', '0')
	->attr('width', '100%')
	->column()
		->title('Country')
		->value(function ($row) {
			return rand(1,10)%2 ? '<b>'.$row->country.'</b>' : $row->country;
		})
		->filter($filterData)
		->css('color', 'green')
		->css('width', '50%')
		->css('background-color', '#ccc', true)
	->add()
	->column()
		->title('Country Code')
		->value('country_code')
		->filter()
		->css('color', 'red')
		->css('width', '20%')
	->add()
	->column('Phone Code')
		->filter()
		->value('phone_code')
		->css('color', 'red')
		->css('width', '20%')
	->add()
	->column()
		->value(function ($row) {
			return '<a href="country/'.$row->id.'">edit</a>';
		})
		->css('width', '10%')
	->add()
	->plugin('Datatables')
		->param('columnDefs', '[{ "targets": 3, "orderable": false }]')
	->add()
	->render();
?>

Jquery, Datatables should be included. Bootstrap is optional

<!-- JQUERY -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>

<!-- DATATABLES -->
<script src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>

<!-- include Datatables Original css -->
<link href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" rel="stylesheet">

<!-- OR include Bootstrap and Datatables Bootstrap theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<link href="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
<script src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script>

```

## Roadmap

 - [ ] more js table plugins
 - [ ] support for json data
 - [ ] code documentation
 - [ ] code some tests

## Contributing

 - welcome to discuss a features, bugs and ideas.

## License

DataTables is release under the MIT license.

You are free to use, modify and distribute this software, as long as the copyright header is left intact
