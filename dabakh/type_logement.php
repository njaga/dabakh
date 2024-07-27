<?php
session_start();
include 'connexion.php';
if (!isset($_SESSION['fonction'])) {
?>
<script type="text/javascript">
    alert("Veillez d'abord vous connectez !");
    window.location = 'index.php';

</script>
<?php
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Types de logement</title>
		<meta charset="utf-8">
		<?php include 'entete.php'; ?>
	</head>
	<body class="blue-grey lighten-5">
		<?php
		include 'verification_menu_immo.php';
		?>
		<div class="container white">
			<div class="row">
				<div class="col s12 m8	  ">
					<h4 class="center-align">Liste des types de logement </h4>
					<table class="bordered highlight centered ">
						<thead>
							<tr>
								<th  data-field="id"></th>
								<th data-field="categorie">Type du logement</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include 'connexion.php';
							$reponse=$db->query('SELECT * FROM type_logement');
							while ($donnees= $reponse->fetch()) {
								$id=$donnees['0'];
										$type_logement=$donnees['1'];
									echo "<tr >";
										echo "<td> <a class='tooltipped' data-position='top' data-delay='50' data-tooltip='cliquez ici pour modifier' href='m_type_logement.php?id=$id'><i class='material-icons left'>edit</i></a> </td>";
										echo "<td>".$type_logement."</td>";
										//echo "<td class='hide'> <a class='red-text' href='s_type_logement.php?id=$id' onclick='return(confirm(\"Voulez-vous supprimer ce type ?\"))'>Supprimer</a> </td>";
										echo "<tr>";}
									?>
								</tbody>
							</table>
						</div>
						
						<div class="col s12 m4 ">
							<div class="container center-align ">
								<form method="POST" action="type_logement_trmnt.php" name="ajout_type_logement"   >
									<h4 class="center-align" >Ajout de type</h4>
									<div class="input-field  " >
										<input id="type_logement" type="text" class="validate " name="type_logement" >
										<label for="type_logement">Type logement</label>
									</div>
									<div class="input-field center-align">
										<button class="btn  waves-light blue darken-4" type="submit" name="envoyer">Ajouter
										<i class="material-icons right">send</i>
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</body>
			<script type="text/javascript">
				$(document).ready(function(){
				$('.tooltipped').tooltip();
				});
			</script>
			<style type="text/css">
				/* fallback */
			@font-face {
			font-family: 'Material Icons';
			font-style: normal;
			font-weight: 400;
			src: local('Material Icons'), local('MaterialIcons-Regular'),
			url("../digital-library/css/2fcrYFNaTjcS6g4U3t-Y5ZjZjT5FdEJ140U2DJYC3mY.woff2") format('woff2');
			}
			.material-icons {
			font-family: 'Material Icons';
			font-weight: normal;
			font-style: normal;
			font-size: 24px;
			line-height: 1;
			letter-spacing: normal;
			text-transform: none;
			display: inline-block;
			white-space: nowrap;
			word-wrap: normal;
			direction: ltr;
			-webkit-font-feature-settings: 'liga';
			-webkit-font-smoothing: antialiased;
			}
			.centrer{  margin:  auto;
			width: 800px;
			height: 25px;
			position: relative;
			right: 15%;
			
			}
			.ajout{
					position: fixed;
					right: 50px;
					top: 20%;
			}
			h4 {
			color: red;

			}
			th{
				font-size: 35px;
			}
			td{
				font-size: 30px;
			}
			</style>
		</html>