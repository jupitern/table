[![Build Status](https://scrutinizer-ci.com/g/jupitern/table/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jupitern/table/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jupitern/table/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jupitern/table/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/jupitern/table/v/stable.svg)](https://packagist.org/packages/jupitern/table) [![Latest Unstable Version](https://poser.pugx.org/jupitern/table/v/unstable.svg)](https://packagist.org/packages/jupitern/table) [![License](https://poser.pugx.org/jupitern/table/license.svg)](https://packagist.org/packages/jupitern/table)
# jupitern/table
#### HTML table generation with PHP.

Pass your data using:
* JSON, Arrays (associative or not).
* result set using PDO or you favourite framework ORM.
* directly or using ajax requests.
* Integrates easily with your preferred js library.
* more to come...

## Demo:

soon...

## Requirements

PHP 8.0 or higher.

## Installation

Include jupitern/table in your project, by adding it to your composer.json file.
```php
{
    "require": {
        "jupitern/table": "3.*"
    }
}
```

## Usage
```php
// instance Table with instance name
\Jupitern\Table\Table::instance()

// set data for non ajax requests
// using a array
->setData([ [1, 'Peter', '35', '961 168 851'], [2, 'John', '44', '169 853 741'] ])
// using a associative array
->setData([
	['id' => 1, 'name' => 'Peter', 'age' => '35', 'phone' => '961 168 851'],
	['id' => 2, 'name' => 'John', 'age' => '44', 'phone' => '169 853 741'],
])
// using json string
->setData([[1,"Peter","35","961 168 851"],[2,"John","44","169 853 741"]])
// using PDO result or your framework ORM. see example how to grab $data at the end
->setData($data)

// add attributes to the <table> html tag one by one
->attr('table', 'id', 'demoTable')
->attr('table', 'class', 'table table-bordered table-striped table-hover')
->attr('table', 'cellspacing', '0')

// or add all <table> attributes at once
->attrs('table', ['class' => 'table table-bordered', 'cellspacing' => '0'])

// add attributes to the table rows
->css('tr', 'background-color', 'red')

// add attributes to the table rows using a callable
->attr('tr', 'data-id', function($row) {
    return 'row-' . $row['id'];
})

// add a new column for array data
->column()
	->title('Name')
	->value(1)
->add()

// add a new column for (associative array, PDO or ORM) data
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

// another closure example for adding a column with edit action with no title on <th>
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
	->attr('th', 'data-val', 'foo')		        // add attributes to <th>
    ->css('th', 'background-color', '#f5f5f5')	// add css to <th>
    ->attr('td', 'data-val', 'bar')				// add attributes to <td>
    ->css('td', 'background-color', '#f5f5f5')	// add css to <td>
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

\Jupitern\Table\Table::instance()
	->setData($data)
	->attr('table', 'id', 'demoTable')
	->attr('table', 'class', 'table table-bordered table-striped table-hover')
	->attr('table', 'cellspacing', '0')
	->attr('table', 'width', '100%')
	->column()
		->title('Name')
		->value(function ($row) {
			return rand(1,10)%2 ? '<b>'.$row->name.'</b>' : $row->name;
		})
		->filter($filterData)
		->css('td', 'color', 'green')
		->css('td', 'width', '50%')
		->css('td', 'background-color', '#ccc', true)
	->add()
	->column()
		->title('Age')
		->value('age')
		->filter()
		->css('td', 'color', 'red')
		->css('td', 'width', '20%')
	->add()
	->column()
	    ->title('Phone')
		->filter()
		->value('phone')
		->css('td', 'color', 'red')
		->css('td', 'width', '20%')
	->add()
	->column()
		->value(function ($row) {
			return '<a href="country/'.$row->id.'">edit</a>';
		})
		->css('td', 'width', '10%')
	->add()
	->render();
?>

Include Jquery, Datatables and Bootstrap (optional) in your html.

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

```

## Roadmap

 - [ ] add demo and more examples
 - [ ] code some tests

## Contributing

 - welcome to discuss a bugs, features and ideas.

## License

jupitern/table is release under the MIT license.

You are free to use, modify and distribute this software, as long as the copyright header is left intact
