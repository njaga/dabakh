<?php
session_start();
include 'connexion.php';
$annee=$_POST['annee'];
$req=$db->prepare("SELECT SUM(mensualite_bailleur.commission), mensualite_bailleur.mois 
FROM `mensualite_bailleur` 
WHERE mensualite_bailleur.annee=?
GROUP BY mensualite_bailleur.mois");
$req->execute(array($annee));
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
label: "Commission de gérance",
data: [<?=$data1 ?> ],
backgroundColor: [
'#bcaaa4 ',
],
borderColor: [
'#6d4c41 ',
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