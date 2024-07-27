<?php
/*try {
	$db = new PDO('mysql:host=localhost;dbname=zulf8960_dabakh;charset=UTF8', 'zulf8960_dabakh', 'Pwd@Vigilus');
} catch (Exception $e) {
	echo "Erreur " . $e->getMessage();
}
$db->query("SET lc_time_names = 'fr_FR';");*/

try {
	$db = new PDO('mysql:host=localhost;dbname=zulf8960_dabakh;charset=UTF8', 'root', '');
} catch (Exception $e) {
	echo "Erreur " . $e->getMessage();
}
$db->query("SET lc_time_names = 'fr_FR';");
?>
