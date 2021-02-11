<?php

$misterXId = "MisterX@gmail.com";
$print_="";
$tmp_="";

$taxi = "./project/img/pion_taxi.jpg";
$bus = "./project/img/pion_bus.jpg";
$metro = "./project/img/under.jpg";
$bateau = "./project/img/pion_black.jpg";

if(isset($_POST['cree'])) {
    $mail = mysqli_real_escape_string($conn, $_POST['email']);
    $nbdetective = mysqli_real_escape_string($conn, $_POST['nbdetective']);
    $strategie = mysqli_real_escape_string($conn, $_POST['strategie']);

    if (empty($mail)) {
        $print_ = $print_.  "Veuillez insérer un mail.<br/>";
    }
    else {

        $parts = explode("@", $mail);
        $username = $parts[0];
        $print_ = $print_ . "Bonjour " . $username . " !<br/><br/>";

        #1. Créer un configuration par défaut
        $nom_conf_default = "Conf_0";

        if ($strategie == "Econome") {
            $req = 'INSERT INTO `Configuration` (nomConfiguration, dateConf, strategie, images) VALUES ("' . $nom_conf_default . '", "' .
                date('d-m-Y') . '", "' . $strategie . '", "' . implode("','",[$taxi, $bus, $metro, $bateau]) . '");';
        }
        else{
            $req = 'INSERT INTO `Configuration` (nomConfiguration, dateConf, strategie) VALUES ("' . $nom_conf_default . '", "' .
                date('d-m-Y') . '", "' . $strategie . '");';
        }

        $res = mysqli_query($conn, $req);
        if ($res) {
            $print_ = $print_ . "Une configuration a été choisie avec la stratégie: " . $strategie . ".<br/>";
            $print_ = $print_ . "Nombre de détectives choisi: " . $nbdetective . ".<br/>";
        } else {
            $print_ = $print_ . "Erreur lors de la création de la configuration.<br/>";
        }

        #2. Créer une partie

        $last_conf_id = 'SELECT idConfiguration FROM `Configuration` ORDER BY idConfiguration DESC LIMIT 1';
        $res = mysqli_query($conn, $last_conf_id);

        while ($row = mysqli_fetch_row($res)) {
            $req = 'INSERT INTO `Partie` (dateDemarrage, nbDetectives, idConfiguration) VALUES 
                    ("' . date('d-m-Y') . '", "' . $nbdetective . '", "' . $row[0] . '");';
        }

        $res = mysqli_query($conn, $req);
        if ($res) {
            $print_ = $print_ . "Initialisation de la partie... <br/>";
        } else {
            $print_ = $print_ . "Erreur lors de la création de la partie.<br/>";
        }

        #3. Créer une joueuese

        # Mais aussi création de n-1 detectives
        # Et création de 1 Mister X.

        $last_partie_id = 'SELECT idPartie FROM `Partie` ORDER BY idPartie DESC LIMIT 1';
        $res_last_partie_id = mysqli_query($conn, $last_partie_id);
        $id_partie = 0;
        while ($row = mysqli_fetch_row($res_last_partie_id)) {
            $id_partie = intval($row[0]);
        }
        $print_ = $print_ . "<br/>";

        $print_ = $print_ . "Création de Mister X... <br/>";
        $id_quartier = 'SELECT codeInsee, idQ, nomQ, typeQ, departement  FROM `Quartier` WHERE isQuartierDepart=' . 1 . ' ORDER BY RAND( ) LIMIT 1';
        $res_id_quartier = mysqli_query($conn, $id_quartier);
        while ($row2 = mysqli_fetch_row($res_id_quartier)) {
            $code_insee = intval($row2[0]);
            $nomQ = $row2[2];
            $typeQ = $row2[3];
            $departement = intval($row2[4]);
            $idQ = intval($row2[1]);

            $req = 'INSERT INTO `Joueuse` (mail, nomJoueuse, idPartie, codeInsee) VALUES
                            ("' . $misterXId . '", "MisterX", "' . $id_partie . '", "' . $code_insee . '");';
            $res = mysqli_query($conn, $req);
        }
        $print_ = $print_ . '=> Mister X se trouve dans le quartier :"' . $nomQ . '" du type: "' . $typeQ . '", dans le : ' . $departement . '<br>';

        # Sauvegrader les tours de Mister x
        $req = 'SELECT idRoute, transport FROM `Route` WHERE idQuartierDepart=' . $idQ . ';';
        $res = mysqli_query($conn, $req);
        while ($row = mysqli_fetch_row($res)) {
            $idRoute = $row[0];
            $transport = $row[1];
        }
        $req = 'INSERT INTO `TourMX` (idRoute, nomQ, transport) VALUES ("' . $idRoute . '", "' . $nomQ . '", "' . $transport .'");';
        $res = mysqli_query($conn, $req);
        # Sauvegrader les tours de Mister x FIN

        $print_ = $print_ . "Création de Mister X FINIE<br/>";

        $print_ = $print_ . "<br/>";
        $print_ = $print_ . "Création des détectives... <br/>";
        for ($i = 1; $i < $nbdetective; $i++) {
            $id_quartier = 'SELECT codeInsee, nomQ, typeQ, departement  FROM `Quartier` WHERE isQuartierDepart=' . 1 . ' ORDER BY RAND( ) LIMIT 1';
            $res_id_quartier = mysqli_query($conn, $id_quartier);
            while ($row2 = mysqli_fetch_row($res_id_quartier)) {
                $code_insee = intval($row2[0]);

                $tmp_ = $tmp_ . '<input id="code_insee_detective_' . $i . '" name="code_insee_detective_' . $i . '" type="hidden" value='. $code_insee .'>';

                $nomQ = $row2[1];
                $typeQ = $row2[2];
                $departement = intval($row2[3]);
                $req = 'INSERT INTO `Joueuse` (mail, nomJoueuse, idPartie, codeInsee) VALUES
                            ("detective' . $i . '@gmail.com", "name_' . $i . '", "' . $id_partie . '", "' . $code_insee . '");';
                $res = mysqli_query($conn, $req);
                $print_ = $print_ . '=> Le detective' . $i . ' se trouve dans le quartier :"' . $nomQ . '" du type: "' . $typeQ . '", dans le : ' . $departement . '<br>';
            }
        }
        $print_ = $print_ . "Création des détectives FINIE. <br/>";
        $print_ = $print_ . "<br/>";

        $id_quartier = 'SELECT codeInsee, nomQ, typeQ, departement  FROM `Quartier` WHERE isQuartierDepart=' . 1 . ' ORDER BY RAND( ) LIMIT 1';
        $res_id_quartier = mysqli_query($conn, $id_quartier);
        $code_insee = 0;
        while ($row2 = mysqli_fetch_row($res_id_quartier)) {
            $code_insee = intval($row2[0]);
            $nomQ = $row2[1];
            $typeQ = $row2[2];
            $departement = intval($row2[3]);
        }

        $req = 'INSERT INTO `Joueuse` (mail, nomJoueuse, idPartie, codeInsee) VALUES
                            ("' . $mail . '", "' . $username . '", "' . $id_partie . '", "' . $code_insee . '");';

        $res = mysqli_query($conn, $req);
        if ($res) {
            $print_ = $print_ . 'La partie ' . $id_partie . ' peut commencer ... ';
            $print_ = $print_ . "<br/>";
            $print_ = $print_ . '=> Vous êtes dans le quartier :"' . $nomQ . '" du type: "' . $typeQ . '", dans le : ' . $departement . '<br><br>';
        } else {
            $print_ = $print_ . "La partie ne peut commencer ! <br/>";
        }

        echo $print_;
        echo '<form action="game.php" method="post">
            <button type="submit" name="next_step" class="btn btn-info">Jouer</button><br>
            <input id="idQ" name="idQ" type="hidden" value='. $idQ .'>
            <input id="idPartie" name="idPartie" type="hidden" value='. $id_partie .'>
            <input id="mailMX" name="mailMX" type="hidden" value='. $misterXId .'>
            
            <input id="code_insee_joueuse" name="code_insee_joueuse" type="hidden" value='. $code_insee .'>
            <input id="mailJoueuse" name="mailJoueuse" type="hidden" value='. $mail .'>
            
            <input id="nbdetective" name="nbdetective" type="hidden" value='. $nbdetective .'> 
            
            <input id="tour" name="tour" type="hidden" value='. 1 .'> '

            .  $tmp_ .

            '</form>';
    }
}

?>
