<?php include('config.php'); ?>

<?php

$servername = "localhost";
$username = "p1716838";
$password = "939966";
$database = "dataset";

$connexion1 = getConnexionBD($servername, $username, $password, $database);
$connexion2 = $conn;

populateQuartier ($connexion1, $connexion2);
populateRoute ($connexion1, $connexion2);

function populateQuartier ($connexion1, $connexion2) {
    $requete1 = "SELECT * FROM Quartiers";
    $res1 = mysqli_query($connexion1, $requete1);
    if ($res1 != FALSE && mysqli_num_rows($res1) != 0){

        $return = False;
        while ($row = mysqli_fetch_row($res1)) {

            $is_quartier_depart = 'SELECT isQuartierDepart FROM `Routes` WHERE idQuartierDepart='.intval($row[0]).';';
            $res_quartier_depart =  mysqli_query($connexion1, $is_quartier_depart);

            while ($row2 = mysqli_fetch_row($res_quartier_depart)){
                $req = 'INSERT INTO `Quartier` (codeInsee, idQ, coords, typeQ, nomQ, cpCommune, nomCommune, departement, isQuartierDepart) VALUES ("'. intval($row[1]).'", "'.intval($row[0]).'", "'.
                    $row[2].'", "'.$row[3].'", "'.$row[4].'", "'.intval($row[5]).'", "'.$row[6].'", "'.intval($row[7]).
                    '", "'.intval($row2[0]).'");';
            }



            $res =  mysqli_query($connexion2, $req);

            $return = $res;
        }
        if($return) {
            echo "Les données de la table Quartier ont bien été importées.";
        }
    }
    return 1;
}


function populateRoute ($connexion1, $connexion2) {
    $requete1 = "SELECT * FROM Routes";
    $res1 = mysqli_query($connexion1, $requete1);
    if ($res1 != FALSE && mysqli_num_rows($res1) != 0){

        $return = False;
        while ($row = mysqli_fetch_row($res1)) {

            $req = 'INSERT INTO `Route` (idQuartierDepart, idQuartierArrivee, transport) VALUES ("'. intval($row[0]).'", "'.intval($row[1]).'", "'.
                $row[2].'");';

            $res =  mysqli_query($connexion2, $req);

            $return = $res;
        }
        if($return) {
            echo "Les données de la table Routes ont bien été importées.";
        }
    }
    return 1;
}

?>