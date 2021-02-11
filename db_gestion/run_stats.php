<?php include('config.php'); ?>

<?php
printStats($conn);


function printStats($connexion) {
    echo '<p>Statistiques des meilleures joueuses : </p>';
    echo '<table class="table table-striped table-bordered"><thead class="thead-dark"><tr>';
    printf("<th>Classement</th>");
    printf("<th>Nom</th>");
    printf("<th>Mail</th>");
    echo "</tr></thead><tbody>";
    $req = 'SELECT mail, nomJoueuse FROM `Joueuse` WHERE statutJoueuse='. 1 .' ORDER BY statutJoueuse DESC LIMIT 3;';
    $res =  mysqli_query($connexion, $req);
    $i = 1;
    while ($row2 = mysqli_fetch_row($res)){
        echo "<tr>";
        printf("<td>%s</td>", $i);
        printf("<td>%s</td>", $row2[1]);
        printf("<td>%s</td>", $row2[0]);
        echo "</tr>";
        $i = $i +1;
    }

    echo "</tbody></table>";
}

?>