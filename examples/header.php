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

	<!-- highlight.js -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/styles/default.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/styles/tomorrow-night.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

	<script type="text/javascript">
		$(document).ready(function() {
			$('pre code').each(function(i, block) {
				hljs.configure({
					tabReplace: '    '
				});
				hljs.highlightBlock(block);
			});
		});
	</script>

	<style type="text/css">
		body{ font-family: 'Open Sans', sans-serif; }
		a { text-decoration: none; }

	</style>

</head>
<body>

<br clear="both"/>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2>Easy PHP table generation</h2>

			<b>github:</b> <a href="https://github.com/jupitern/table" target="_blank">jupitern/table</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>author:</b> <a href="http://nunochaves.com" target="_blank">Nuno Chaves (JupiterN)</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<b>Documentation:</b> <a href="https://github.com/jupitern/table#usage" target="_blank">here</a> <br/><br/>
		</div>
	</div>
	<br/>
	<div class="row">
		<div class="col-md-6 col-xs-12">

			<p style="padding-left: 18px; line-height: 22px;">

				<b>Pass your data using:</b> <br/><br/>

				JSON, Arrays (associative or not).<br/>
				result set using PDO or you favourite framework ORM.<br/>
				directly or using ajax requests.<br/><br/>
			</p>

		</div>
		<div class="col-md-6 col-xs-12">

			<p style="padding-left: 18px; line-height: 22px;">
				<b>Give some power to you tables with your preferred js library:</b> <br/><br/>

				Datatables (tested with v1.10.4).<br/>
				more to come...<br/>
				easily extensible to add your custom plugin render<br/>
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">

			<br clear="both" />
			<br clear="both" />

			<ul class="nav nav-pills">
				<li role="presentation"><a href="#"><b>EXAMPLES:</b></a></li>
				<li role="presentation"><a href="index.php">Array</a></li>
				<li role="presentation"><a href="arrayAssoc.php">Associative Array</a></li>
				<li role="presentation"><a href="pdo.php">PDO or ORM</a></li>
				<li role="presentation"><a href="datatables.php">Datatables</a></li>
			</ul>

		</div>
	</div>
</div>

<br/><br/>