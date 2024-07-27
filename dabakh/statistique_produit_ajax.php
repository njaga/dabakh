<?php
session_start();
include 'connexion.php';
$annee=$_POST['annee'];
$req=$db->prepare("SELECT produit.produit, COUNT(vente_produit.quantite) 
FROM `vente_produit`, produit, consultation
WHERE vente_produit.id_consultation=consultation.id_consultation AND vente_produit.id_produit=produit.id AND YEAR(consultation.date_consultation)=?
GROUP BY produit.id");
$req->execute(array($annee));


$color=array("#c62828", "#6a1b9a","#bbdefb","#80cbc4","#827717","#e65100","#4e342e","#ab47bc","#d50000","#1a237e","#004d40","#d4e157","#e65100","#8d6e63","#37474f","#f57f17","#004d40","#01579b","#e91e63","#673ab7","#006064","#ff9800","#8d6e63","#ffd180","#b9f6ca","#efebe9");
$data="";
$produit="";
$couleur="";
$nbr=0;
$i=0;

while ($donnees=$req->fetch()) 
{
  $prod=$donnees['0'];
  $nb=$donnees['1'];
 if ($nb>=10) 
 {
    $data=$data.','.$donnees['1'];
    $produit=$produit.',"'.$donnees['0'].'"';
    $couleur=$couleur.',"'.$color[$i].'"';
    $i++;
 }
 else
 {
  $nbr=$nbr+$donnees['1'];
 }
}
$data=$data.",".$nbr;
$produit=$produit.',"Autres produits"';
$couleur=$couleur.',"#000000 "';
$data=substr($data, 1);
$produit=substr($produit, 1);
$couleur=substr($couleur, 1);

?>
      <canvas class="col-md-10" id="labelChart"></canvas>
<script type="text/javascript">
  var ctxP = document.getElementById("labelChart").getContext('2d');
var myPieChart = new Chart(ctxP, {
  plugins: [ChartDataLabels],
  type: 'pie',
  data: {
    labels: [<?=$produit ?>],
    datasets: [{
      data: [<?=$data ?>],
      backgroundColor: [<?=$couleur ?>],
      hoverBackgroundColor: [<?=$couleur ?>]
    }]
  },
  options: {
    responsive: true,
    legend: {
      position: 'right',
      labels: {
        padding: 20,
        boxWidth: 10
      }
    },
    plugins: {
      datalabels: {
        formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value * 100 / sum).toFixed(2) + "%";
          return percentage;
        },
        color: 'white',
        labels: {
          title: {
            font: {
              size: '16'
            }
          }
        }
      }
    }
  }
});
</script>