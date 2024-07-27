<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
if($_SESSION['fonction']=="stagiaire")
{
include 'nav_immo_stagiaire.php';
}
else
{
    include 'nav_immo.php';

}
?>