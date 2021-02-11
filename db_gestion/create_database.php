<?php include('config.php'); ?>
<?php

    $sql = "DROP DATABASE $database";
    mysqli_query($conn, $sql);

    $sql = "CREATE DATABASE IF NOT EXISTS $database";

    if (mysqli_query($conn, $sql)) {
        echo "La base de données a été créée avec succès<br>";
    } else {
        echo "Erreur lors de la création de la base de données: <br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
?>