# datatables

agnostic framework wrapper for datatables

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
````
// instance Datatables with instance name
echo \Jupitern\Datatables\Datatables::instance('dt_example')

// set data for non ajax requests
->setData($data)

// add any attribute to the table html tag
->attr('class', 'table table-bordered table-striped table-hover')
->attr('cellspacing', '0')

// add a column in one line
->column('Column Title')->value('db_column_name')->add()

// add a column with text field as filter
->column('Column Title')->value('db_column_name')->filter()->add()

// add a column with a drop down as filter
// data must be a collection of objects as shown in example bellow
->column('Column Title')->value('db_column_name')->filter($data)->add()

// add a column with a closure for value field to process data in execution
// row represents a row object of the data collection
->column('Column Title')
	->value(function ($row) {
		return rand(1,10)%2 ? '<b>'.$row->db_column_name.'</b>' : $row->db_column_name;
	})
->add()

// css for row field and header
->column('Column Title')
	->value('db_column_name')
	->css('color', 'red')		// add css to field <td>
	->css('width', '20%', true)	// add css to field <th>
->add()

// echo table output
->render();

// OR return table output
->render(true);

````


## Examples
````
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

	// init datatables
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

Jquery, Datatables should be included. Bootstrap is optional

<!-- JQUERY -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>

<!-- BOOTSTRAP -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<!-- DATATABLES -->
<link href="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<!-- include Datatables Original css -->
<link href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" rel="stylesheet">
<!-- or include Datatables Bootstrap css -->
<script src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script>

````
