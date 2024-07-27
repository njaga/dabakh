<!DOCTYPE html>
<html>
	<head>
		<title>Traitement de l'ajout</title>
		<?php include 'entete.php'; ?>
	</head>
	<body>
		<?php
		include 'connexion.php';
		include 'supprim_accents.php';
		$prenom=htmlspecialchars(ucfirst(strtolower(suppr_accents($_POST['prenom']))));
		$nom=htmlspecialchars(strtoupper(suppr_accents($_POST['nom'])));
		$telephone=htmlspecialchars($_POST['telephone']);
		$fonction=htmlspecialchars($_POST['fonction']);
		$service=htmlspecialchars($_POST['service']);
		$date_embauche=htmlspecialchars($_POST['date_embauche']);
		$sexe=htmlspecialchars($_POST['sexe']);
		$matricule=htmlspecialchars($_POST['matricule']);
		
		$reponse=$db->prepare('SELECT COUNT(*) FROM personnel WHERE nom=?');
		$reponse->execute(array($nom));
		$donnee= $reponse->fetch();
		$nbr=($donnee['0'] + 1);
		if ($nbr==1)
		{
			$login= strtolower(str_replace(" ", "_", $nom))."@dabakh";
		}
		else
		{
			$login= strtolower(str_replace(" ", "_", $nom)).$nbr."@dabakh";
		}
		$pwd="pwd".strtolower($nom);
		$req=$db->prepare('INSERT INTO personnel(prenom, nom, telephone, fonction, date_embauche, login, pwd, service,sexe, matricule, etat) values (?,?,?,?,?,?,?,?,?,?,"activer") ');
		$req->execute(array($prenom, $nom, $telephone, $fonction, $date_embauche, $login, sha1($pwd), $service, $sexe, $matricule)) or die(print_r($req->errorInfo()));
		$nbr=$req->rowCount();
		$id_personnel=$db->lastInsertId();
		if ($nbr>0)
		{
			//upload des pièces jointes
			$nbr_pj = count($_FILES['fichier']['name']);
			for ($i=0; $i < $nbr_pj; $i++)
			{
				switch ($_FILES['fichier']['error'][$i])
				{
					case 1: // UPLOAD_ERR_INI_SIZE
					$error="Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !";
					break;
					case 2: // UPLOAD_ERR_FORM_SIZE
					$error= "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
					break;
					case 3: // UPLOAD_ERR_PARTIAL
					$error= "L'envoi du fichier a été interrompu pendant le transfert !";
					break;
					case 4: // UPLOAD_ERR_NO_FILE
					$doc='';
					break;
					default:
					{
						// Testons si l'extension est autorisée
						$extension= strrchr($_FILES['fichier']['name'][$i], '.');
						$extensions_autorisees = array('.pdf', '.jpg', '.jpeg', '.png');
						if (!in_array($extension, $extensions_autorisees))
						{
							$error='Seul les fichiers d\'extension pdf sont autorisé, contacter votre administrateur pour plus de renseignements';
						}
						else
						{
							$repertoire='../www/pj/personnel/'.$id_personnel.'/';

							if (is_dir($repertoire))
							{
								
							}
							else
							{
								mkdir($repertoire);
							}
							$nom_fichier=str_replace("'", "", suppr_accents($_FILES['fichier']['name'][$i]));
							move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repertoire.$nom.$extension);
							$doc=$repertoire='../pj/personnel/'.$id_personnel.'/'.$nom;
							$req=$db->prepare('INSERT INTO pj_personnel(nom, chemin, id_personnel) VALUES (?,?,?)');
							$req->execute(array($nom_fichier, $doc, $id_personnel)) or die(print_r($req->errorInfo()));
						}
					}
				}
		
			}
		//Fin
		?>
		<!-- Modal Structure -->
		<div id="valide" class="modal">
			<div class="modal-content">
				<h4 class="center">Inscription validée</h4>
				<h5>Le nouveau membre <span> <b> <?= $nom ?> <?= $prenom ?> </b></span> est bien enregistrer.</h5>
				<h5>Son login est : <b> <?= $login ?></b></h5>
				<h5>Son mot de passe est : <b> <?php echo"pwd".strtolower(str_replace(" ", "_", $nom)) ?></b></h5>
			</div>
			<div class="modal-footer">
				<a href="l_personnel.php" class="modal-action modal-close waves-effect waves-green btn-flat"><h6>OK</h6></a>
			</div>
		</div>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			alert('Erreur enregistrement non éffectué');
			window.location="e_personnel.php";
		</script>
		<?php
		}
		?>
	</body>
	<script type="text/javascript">
	$(document).ready(function(){
	$('.modal').modal();
	$('#valide').modal('open');
	});
	
	</script>
	<style type="text/css">
		span{
			color: #0d47a1 ;
		}
	</style>
</html>