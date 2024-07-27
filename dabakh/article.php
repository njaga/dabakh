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
		<title>Articles de l'établissement</title>
		<?php include 'entete.php'; ?>		
	</head>
	<body class="blue-grey lighten-5">
        <?php
		include 'verification_menu_cm.php';
		?>
		<div class=" white">
			<div class="row">
				<h3 class="center-align col s12 m12"><u>Articles de l'établissement </u></h3>
				<div class="col s4 offset-s1 input-field white" style="border-radius: 45px; border: 2px black solid">
					<i class="material-icons prefix">search</i>
					<input type="text" name="search" id="search">
					<label for="search">Article</label>
				</div>
				<div class="col s3">
				<a class='waves-effect waves-light  modal-trigger btn' href='#modal0'>Ajouter +</a>
				</div>
				<div class="col s12 m12">
					<table class="bordered highlight centered ">
						<thead>
							<tr style="color: #fff; background-color: #bd8a3e">
								<th></th>
								<th  data-field="id"></th>
								<th data-field="">Article</th>
                                <th data-field="">Pu</th>
                                <th data-field="">Quantité restante</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
				<!-- Modal Structure ajout article-->
				<div id="modal0" class="modal">
					<div class="modal-content ">
						<form method="POST" action="article_trmnt.php"   >
                            <p class="center-align" >Ajout article</p>
                            <div class="row">
								<div class="input-field  col s6" >
									<input id="article" type="text" class="validate " name="article" >
									<label for="article">Article</label>
								</div>
							</div>
                            <div class="row">
								<div class="input-field  col s3" >
									<input id="qt" type="number" class="validate " name="qt" >
									<label for="qt">Quantité</label>
								</div>
                                <div class="input-field  col s3" >
									<input id="pu" type="number" class="validate " name="pu" >
									<label for="pu">PU de vente</label>
								</div>
								<div class="input-field  col s6" >
									<input id="montant" type="number" class="validate " name="montant" >
									<label for="montant">Montant acheté</label>
								</div>
                            </div>
							<div class="row">
								<div class="input-field  col s3" >
									<input id="pj" type="number" class="validate " name="pj" >
									<label for="pj">N° Pièce Jointe</label>
								</div>
							</div>
                            <div class="input-field  center-align">
                                <button class="btn  waves-light blue darken-4" type="submit" name="envoyer">Ajouter
                                <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </form>
					</div>
					<div class="modal-footer">
					<a href="#!" class="modal-close waves-effect waves-green btn btn-flat red">Annuler</a>
					</div>
				</div>
                
			</div>
			</body>
			<script type="text/javascript">
				$('.modal').modal();
				$('.tooltipped').tooltip();
				function article()
				{
					var search = $('input:first').val();
					$.ajax({
					type:'POST',
					url:'article_ajax.php',
					data:'search='+search,
					success:function (html) {
						$('tbody').html(html);
					}
					});
				}
				article();
				$('input:first').keyup(function(){
				article();
				});
			</script>
			<style type="text/css">
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
			p {
			color: red;
			font-size: 28px;
			font-family: Cambria, Georgia, serif;
			}
			</style>
		</html>