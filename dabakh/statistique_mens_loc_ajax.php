<?php
session_start();
include 'connexion.php';

$annee=$_POST['annee'];
$id=$_POST['id'];
$req=$db->prepare("SELECT montant, mois 
FROM mensualite
INNER JOIN location ON mensualite.id_location=location.id
INNER JOIN locataire ON locataire.id=location.id_locataire
WHERE locataire.id=? AND mensualite.annee=?");
$req->execute(array($id, $annee)); 

$nbr=$req->rowCount();

$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");

$data=array();
 for ($i=1; $i <=12 ; $i++) { $data[$i]=0;} 
while ($donnees=$req->fetch()) 
{

   for ($i=1; $i <=12 ; $i++) 
   { 
     if ($donnees['1']==$mois[$i]) 
     {
       $data[$i]=$donnees['0'];
        if (is_null($donnees['0'])) 
        {
          $data[$i]=0;
        }
       break;
     }
   }
}
$data1='"'.$data[1].'"';
for ($i=2; $i <=12 ; $i++) 
  { 
    $data1=$data1.',"'.$data[$i].'"';
  }
?>
<canvas class="col-md-12 col-lg-12" id="lineChart"></canvas>

<script type="text/javascript">
  //line
var ctxL = document.getElementById("lineChart").getContext('2d');
var myLineChart = new Chart(ctxL, {
type: 'line',
data: {
labels: ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre"],
datasets: [
{
label: "Recouvrement effectués",
data: [<?=$data1 ?> ],
backgroundColor: [
'#e1bee7 ',
],
borderColor: [
'#e040fb  ',
],
borderWidth: 2
}
]
},
options: {
responsive: true
}
});
</script>