<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

	<title>Consulta de linhas</title>
	<style type="text/css">
		.line-hor{
			width: 85px;
			background: #ddd;
			border-radius: 5px;
			padding: 3px;
			margin: 5px auto;
			position: relative;
			overflow: hidden;
		}
		.line-hor.bg-info{
			color: #fff;
		}
		.line-hor i{
			display: none;
		}
		.line-hor.bg-info i{
			display: block;
			position: absolute;
			top: -5px;
			right: 0;
			font-size: 40px;
			opacity: 0.2;
			color: #555 !important;
		}
	</style>
</head>
<body>
	<div class="container pb-5">
		<div class="row">
			<h1>Consultar</h1>
		</div>
		<div class="row">
			<form method="post" class="form col-md-12">
				<input type="text" name="linha" class="form-control mb-3" id="linha" placeholder="Pesquise pela linha...">
				<input type="date" name="date" value="<?= date("Y-m-d"); ?>" class="form-control col-md-4">
				<button type="submit" class="btn btn-success mt-3">Enviar</button>
			</form>
		</div>	
	</div>
	<?php 
	$lines = file_get_contents("http://gistapis.etufor.ce.gov.br:8081/api/linhas/");
	$json = json_decode($lines);
	$linhas = array();
	foreach ($json as $key => $line) {
		$linhas[]=$line->numeroNome;
	}
	$_linhas = implode('","', $linhas);

	if ($_POST) {
		$get= explode("-", $_POST['linha']);

		$today = file_get_contents("http://gistapis.etufor.ce.gov.br:8081/api/horarios/".$get[0]."?data=".str_replace("-", "", $_POST['date']));
		$_json=json_decode($today);
		$ida=$_json[0];
		$volta=$_json[1];
		//var_dump($ida);
	}
	?>

	<?php if($_POST){ ?>
		<div class="container">
			<div class="row mb-3">
				<div class="col-md-12">
					<h3><?= $ida->postoControle; ?> <i class="fas fa-angle-double-right"></i> <?= $volta->postoControle; ?></h3>
					<hr>
					<div class="content-lines">
						<?php foreach ($ida->horarios as $key => $line) { ?>
							<div class="line-hor d-inline-block <?php if($line->acessivel=='sim'): echo 'bg-info'; endif; ?>">#<?= $line->tabela; ?>: <?= $line->horario; ?><i class="fas fa-wheelchair"></i></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3><?= $volta->postoControle; ?> <i class="fas fa-angle-double-right"></i> <?= $ida->postoControle; ?></h3>
					<hr>
					<div class="content-lines">
						<?php foreach ($volta->horarios as $key => $line) { ?>
							<div class="line-hor d-inline-block <?php if($line->acessivel=='sim'): echo 'bg-info'; endif; ?>">#<?= $line->tabela; ?>: <?= $line->horario; ?><i class="fas fa-wheelchair"></i></div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script>
		$(function() {
			var esportes = [
			"<?= $_linhas; ?>"
			];
			$("#linha" ).autocomplete({
				source: esportes
			});
		});
	</script>
</body>
</html>