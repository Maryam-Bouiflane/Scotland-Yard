<?php

$servername = "localhost";
$username = "p1716838";
$password = "939966";
$database = "p1716838";
$conn = getConnexionBD($servername, $username, $password, $database);

function getConnexionBD($server, $user, $mdp, $db) {
    $connexion = mysqli_connect($server, $user, $mdp, $db);
    if (mysqli_connect_errno()) {
        printf('<p class="bg-danger">Échec de la connexion : %s</p>', mysqli_connect_error());
        exit();
    }
    mysqli_query($connexion,'SET NAMES UTF8');
    return $connexion;
}
?>