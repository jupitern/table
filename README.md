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
todo

## Examples
````
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
````
