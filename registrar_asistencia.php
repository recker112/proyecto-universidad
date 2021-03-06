<?php
	require 'php/DB.php';
	require 'php/controllers/loginController.php';
	require 'php/controllers/BeneficiaryController.php';
	require 'php/controllers/EmployeeController.php';
	require 'php/controllers/AssistanceBenController.php';
	require 'php/controllers/AssistanceEmpController.php';
	
	//Init session
	session_start();

	//Verify login
	if (!$_SESSION['auth']) {
		header('location: index.php');
	}

	//StatusBox
	if(isset($_SESSION['statusBox'])) {
		$dataStatus = array(
			'message' => $_SESSION['statusBox_message'],
			'status' => $_SESSION['statusBox'],
		);
		
		unset($_SESSION['statusBox']);
		unset($_SESSION['statusBox_message']);
	}

	//Form
	if (isset($action)) {
		$controller = new BeneficiaryController();
		$controller2 = new EmployeeController();
		
		$dataPeople = $controller->show();
		
		if ($dataPeople === null) {
			$dataPeople = $controller2->show();
		}
	}

	if (isset($reg)) {
		$controller = new AssistanceBenController();
		$controller2 = new AssistanceEmpController();
		
		if ($regIn === 'ben') {
			$controller->create($reg, $peso);
		}else if ($regIn === 'emp') {
			$controller2->create($reg);
		}
	}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Panel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<?php require('php/componets/HeaderAuth.php') ?>
	
	<main class='container'>
		<div class="row">
			<div class='col s12'>
				<h5>Registrar asistencia</h5>
			</div>
			
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<form class='formProducts' method="POST" action="#" autocomplete='off'>
							<div class='row' style='width: 100%'>
								<div class="input-field col s12">
									<i class="prefix material-icons">search</i>
									<input id="search" 
										type="text" 
										name="search"
									/>
									<label for="search">Buscar cédula de empleado/beneficiario</label>
								</div>
								<div class='col s12 center-align'>
									<button 
										class="btn waves-effect red lighten-1" type="submit" name="action"
									>
										Buscar
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<span class="card-title">Datos de la persona</span>
						<?php if (!empty($dataPeople)): ?>
						<table class="striped centered">
							<thead>
								<tr>
									<?php if (isset($dataPeople['seguimiento'])): ?>
									<th>Cédula</th>
									<th>Nombres</th>
									<th>Apellidos</th>
									<th>Sexo</th>
									<th>Nacimiento</th>
									<th>Peso</th>
									<th>Registrado por</th>
									<th>Seguimiento de peso</th>	
									<?php elseif (isset($dataPeople['telefono'])): ?>
									<th>Cédula</th>
									<th>Nombres</th>
									<th>Apellidos</th>
									<th>Sexo</th>
									<th>Nacimiento</th>
									<th>teléfono</th>
									<th>Registrado por</th>
									<?php endif ?>
								</tr>
							</thead>
							<tbody>
								<?php if (isset($dataPeople['seguimiento'])): ?>
								<tr>
									<td><?php echo $dataPeople['cedula'] ?></td>
									<td><?php echo $dataPeople['nombre'] ?></td>
									<td><?php echo $dataPeople['apellido'] ?></td>
									<td><?php
										if ($dataPeople['sexo']==='M') {
											echo 'Masculino';
										}else {
											echo 'Femenino';
										}
									?></td>
									<td><?php echo $dataPeople['nacimiento'] ?></td>
									<td><?php 
										if (empty($dataPeople['peso'])) {
											echo 'No registrado';
										}else {
											echo $dataPeople['peso'].'Kg';
										}
									?></td>
									<td><?php 
										if ($dataPeople['name']) {
											echo $dataPeople['name'];
										}else {
											echo 'Cuenta eliminada';
										}
									?></td>
									<td><?php
										if ($dataPeople['seguimiento']) {
											echo 'activo';
										}else {
											echo 'desactivado';
										}
									?></td>
								</tr>
								<tr>
									<td colspan="8">
										<form action="registrar_asistencia.php">
										<input type='hidden' name='reg' value='<?php echo $dataPeople['people_id'] ?>' />
										<input type='hidden' name='regIn' value='ben' />
										<?php if ($dataPeople['seguimiento']): ?>
										<div class="input-field col s12">
											<input id="search" 
												type="number" 
												name="peso"
												step="0.1" 
											/>
											<label for="search">Nuevo peso</label>
										</div>
										<?php endif ?>
										<button
											class="btn waves-effect red lighten-1" 
										>
											Registrar asistencia
										</button>
										</form>
									</td>
								</tr>
								<?php elseif (isset($dataPeople['telefono'])): ?>
								<tr>
									<td><?php echo $dataPeople['cedula'] ?></td>
									<td><?php echo $dataPeople['nombre'] ?></td>
									<td><?php echo $dataPeople['apellido'] ?></td>
									<td><?php
										if ($dataPeople['sexo']==='M') {
											echo 'Masculino';
										}else {
											echo 'Femenino';
										}
									?></td>
									<td><?php echo $dataPeople['nacimiento'] ?></td>
									<td><?php 
										if (!empty($dataPeople['telefono'])) {
											echo $dataPeople['telefono'];
										}else {
											echo 'No registrado';
										}
									?></td>
									<td><?php echo $dataPeople['name'] ?></td>
								</tr>
								<tr>
									<td colspan="8">
										<a 
											href="registrar_asistencia.php?reg=<?php echo $dataPeople['people_id'] ?>&regIn=emp" 
											class="btn waves-effect red lighten-1" 
										>
											Registrar asistencia
										</a>
									</td>
								</tr>
								<?php endif ?>
							</tbody>
						</table>
						<?php endif ?>
					</div>
				</div>
			</div>
			
		</div>
		
		<input type='hidden' id='js_statusBox' value='<?php echo json_encode($dataStatus) ?>' />
	</main>
	
	<script src="js/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/statusBox.js"></script>
</body>
</html>