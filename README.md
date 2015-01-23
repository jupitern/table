[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jupitern/datatables/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jupitern/datatables/?branch=master)

# datatables

agnostic framework wrapper for datatables (tested with v1.10.4)
can be used for simple table generation without datatables
can be easily integrated with any framework orm

## Demo:

http://nunochaves.com/dev/datatables/examples/test.php

## Requirements

PHP 5.3 or higher.

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
\Jupitern\Datatables\Datatables::instance('dt_example')

// set data for non ajax requests
// $data is a collection of anonymous objects fetched using PDO (shown in example bellow)
// but you can use your framework ORM
->setData($data)

// add attributes to the <table> html tag one by one
->attr('class', 'table table-bordered table-striped table-hover')
->attr('cellspacing', '0')

// or add all <table> attributes at once
->attrs(['class' => 'table table-bordered', 'cellspacing' => '0']);

// add a column in one line
->column('Column Title')->value('db_column_name')->add()

// add a column with text field as filter
->column('Column Title')->value('db_column_name')->filter()->add()

// add a column with a drop down field as filter
// $data is a collection of anonymous objects fetched using PDO (shown in example bellow)
// but you can use your framework ORM to retrieve data
->column('Column Title')
	->filter($filterData)
	->value('db_column_name')
->add()

// add a column with a closure for value field to process data in execution
// row represents a row object of the data collection
->column('Column Title')
	->value(function ($row) {
		return rand(1,10)%2 ? '<b>'.$row->db_column_name.'</b>' : $row->db_column_name;
	})
->add()

// css for row field and column header
->column('Column Title')
	->value('db_column_name')
	->css('background-color', '#BCC6CC', true)	// add css to field <th>
	->css('width', '20%', true)					// add css to field <th>
	->css('color', 'red')						// add css to field <td>
->add()

// add a column with row actions and no header
->column('')->value(function ($row) {
	return '<a href="country/'.$row->db_column_name.'">edit</a>';
})

// add datatables js init param
->jsParam('paging', 'false')

// add all datatables js init params in one line
// example: disallow order in actions column and set paging to false
->jsParams([
	'columnDefs' => '[{ "targets": 3, "orderable": false }]',
	'paging' => 'false'
])

// output only html table. dont output any js.
->disableJs()

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
$data = $db->query("SELECT * FROM countries")->fetchAll(PDO::FETCH_OBJ);
// used for column filter
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

 - [ ] code documentation
 - [ ] process and retrieve remote data requests
 - [ ] code some tests

## Contributing

 - welcome to discuss a features, bugs and ideas.
