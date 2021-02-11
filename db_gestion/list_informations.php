<?php include('config.php'); ?>

<?php
$connexion = $conn;

$requete = "SELECT * FROM Quartier";
$res = mysqli_query($connexion, $requete);
printResults($res, $connexion);


function printResults($resultats, $connexion) {
    if($resultats === FALSE) {
        global $connexion;
        echo "Problème avec la requête", mysqli_error($connexion);
    }
    else {
        if(mysqli_num_rows($resultats) == 0)
            echo "Aucun tuple résultat !";
        else {

            echo '<table id="myTable" class="table table-striped table-bordered"><thead class="thead-dark"><tr>';
            printf("<th>Identifiant</th>");
            printf("<th>Nom</th>");
            printf("<th>Ville</th>");
            printf("<th>Quartier accessible</th>");
            printf("<th>Transport</th>");
            echo "</tr></thead><tbody>";
            while ($row = mysqli_fetch_row($resultats)) {

                $req = 'SELECT DISTINCT idQuartierArrivee, transport FROM `Route` WHERE idQuartierDepart=' . $row[1] .';';
                $res =  mysqli_query($connexion, $req);

                while ($row2 = mysqli_fetch_row($res)){
                    echo "<tr>";
                    printf("<td>%s</td>", $row[1]);
                    printf("<td>%s</td>", $row[4]);
                    printf("<td>%s</td>", $row[6]);
                    printf("<td>%s</td>", $row2[0]);
                    printf("<td>%s</td>", $row2[1]);
                    echo "</tr>";
                }



            }
            echo "</tbody></table>";
        }
    }
}

?>