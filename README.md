[![Build Status](https://scrutinizer-ci.com/g/jupitern/table/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jupitern/table/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jupitern/table/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jupitern/table/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/jupitern/table/v/stable.svg)](https://packagist.org/packages/jupitern/table) [![Latest Unstable Version](https://poser.pugx.org/jupitern/table/v/unstable.svg)](https://packagist.org/packages/jupitern/table) [![License](https://poser.pugx.org/jupitern/table/license.svg)](https://packagist.org/packages/jupitern/table)
# jupitern/table
#### PHP table generation.
#### Serve your data using Arrays, JSON, PDO or your framework ORM directly or by ajax.
#### Integrate with your favourite js library (datatables or other)

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
->setData([ [1, 'Peter', '35', '961 168 851'], [2, 'John', '44', '169 853 741'] ])
// using a associative array
->setData([
	['id' => 1, 'name' => 'Peter', 'age' => '35', 'phone' => '961 168 851'],
	['id' => 2, 'name' => 'John', 'age' => '44', 'phone' => '169 853 741'],
])
// using json string
->setData('[[1,"Peter","35","961 168 851"],[2,"John","44","169 853 741"]]')
// using PDO result or your framework ORM. see example how to grab $data at the end
->setData($data)

// add attributes to the <table> html tag one by one
->attr('class', 'table table-bordered table-striped table-hover')
->attr('cellspacing', '0')

// or add all <table> attributes at once
->attrs(['class' => 'table table-bordered', 'cellspacing' => '0']);

// add a new column for array data
->column()
	->title('Name')
	->value(1)
->add()

// add a new column for (associtive array, PDO or ORM) data
->column()
	->title('Age')
	->value('age')
->add()

// add a column with a closure for value field to process data in execution
// this example assumes data as object
->column()
	->title('Name')
	->value(function ($row) {
		return rand(1,10)%2 ? '<b>'.$row->name.'</b>' : $row->name;
	})
->add()

// onether closure example for adding a column with edit action with no title on <th>
// this example assumes data associative array
->column()
	->value(function ($row) {
		return '<a href="edit/'.$row['id'].'">edit '.$row['name'].'</a>';
	})
->add()

// add a column with text field as filter
->column()
	->title('Name')
	->value('name')
	->filter()
->add()

// add a column with a drop down field as filter
// $filterData as array
->column()
	->title('Name')
	->value('name')
	->filter([[1, 'Peter'], [2, 'John']])
->add()

// add a column with a drop down field as filter
// $filterData from (associtive array, PDO or ORM). see example how to grab $data at the end
->column()
	->title('Name')
	->value('name')
	->filter($filterData)
->add()

// add a column with some attributes and css for <th> and <td>
->column()
	->title('Name')
	->value('name')
	->attr('data-val', 'foo', true)				// add attributes to <th>
    ->css('background-color', '#f5f5f5', true)	// add css to <th>
    ->attr('data-val', 'bar')					// add attributes to <td>
    ->css('background-color', '#f5f5f5')		// add css to <td>
->add()

// add datatables plugin with some params to your table
// to get some paging ordering and filtering to work
->plugin('Datatables')
	// add this param to grab your data from ajax request
	// this option sets several datatable params at once behind the scenes
	->ajax('http://localhost/getRemoteData.php')
	// add param disable ordering on actions column
	// any datatables params can be added using this function
	->param('columnDefs', '[{ "targets": 3, "orderable": false }]')
->add()

// echo table output
->render();

// OR return table output
->render(true);

```


## Example using PDO and datatables
```php
// grab data from db with PDO or in alternative from your framework ORM
$db = new PDO('mysql:host=HOST_NAME;dbname=DB_NAME;charset=utf8', 'DB_USERNAME', 'DB_PASSWORD',
		array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);
// data to populate table
$data = $db->query("SELECT id, name, age, phone FROM persons")->fetchAll(PDO::FETCH_OBJ);
// used for column filter
$filterData = $db->query("SELECT name as val, name FROM persons limit 10")->fetchAll(PDO::FETCH_OBJ);

\Jupitern\Table\Table::instance('dt_example')
	->setData($data)
	->attr('class', 'table table-bordered table-striped table-hover')
	->attr('cellspacing', '0')
	->attr('width', '100%')
	->column()
		->title('Name')
		->value(function ($row) {
			return rand(1,10)%2 ? '<b>'.$row->name.'</b>' : $row->name;
		})
		->filter($filterData)
		->css('color', 'green')
		->css('width', '50%')
		->css('background-color', '#ccc', true)
	->add()
	->column()
		->title('Age')
		->value('age')
		->filter()
		->css('color', 'red')
		->css('width', '20%')
	->add()
	->column('Phone')
		->filter()
		->value('phone')
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
 - [ ] more examples (including ajax data)
 - [ ] code some tests

## Contributing

 - welcome to discuss a bugs, features and ideas.

## License

jupitern/table is release under the MIT license.

You are free to use, modify and distribute this software, as long as the copyright header is left intact
