<?php

$fin_de_la_partie = false;
$tour = 1;

if(isset($_POST['next_step'])) {

    $idQ = mysqli_real_escape_string($conn, $_POST['idQ']);
    $misterXId = mysqli_real_escape_string($conn, $_POST['mailMX']);
    $id_partie = mysqli_real_escape_string($conn, $_POST['idPartie']);

    $code_insee_joueuse = mysqli_real_escape_string($conn, $_POST['code_insee_joueuse']);
    $mail_joueuse = mysqli_real_escape_string($conn, $_POST['mailJoueuse']);

    $nbdetective = mysqli_real_escape_string($conn, $_POST['nbdetective']);

    $tour = mysqli_real_escape_string($conn, $_POST['tour']);

    for ($i = 1; $i <=1; $i++)
    {

        $req = 'SELECT nomQ, transport FROM `TourMX`;';
        $res =  mysqli_query($conn, $req);
        echo 'Le chemen de Mister X: ';

        while ($row = mysqli_fetch_row($res)){
            printf("<td>%s</td>",  $row[0] . ' avec (' . $row[1]  . ') => ');
        }

        echo '<br>';
        $print_ = "";
        $print_ = $print_ . 'Tour : ' . $tour . '<br>';
        #1. Faire avancer Mister X
        # Trouver tous les quartiers auxquels mister x pourrait y aller
        # Choisir le premier dans lequel il n'y a pas de detectives
        # Update la position de Mister X
        $req_misterX = 'SELECT DISTINCT idQuartierArrivee FROM `Route` WHERE idQuartierDepart=' . $idQ .';';
        $res_misterX =  mysqli_query($conn, $req_misterX);

        while ($row = mysqli_fetch_row($res_misterX)){
            $next_code_quartier = $row[0];
        }

        $req_next_quartier = 'SELECT codeInsee FROM `Quartier` WHERE idQ=' . $next_code_quartier .';';
        $res_next_quartier =  mysqli_query($conn, $req_next_quartier);

        while ($row = mysqli_fetch_row($res_next_quartier)){
            $next_code_insee = $row[0];
        }

        $udpate_misterx_position = 'UPDATE `Joueuse` SET `codeInsee`= '. $next_code_insee .' WHERE idPartie=' . $id_partie . ' and mail=' . $misterXId . ';';
        mysqli_query($conn, $udpate_misterx_position);

        $id_quartier = 'SELECT idQ, nomQ, typeQ, departement  FROM `Quartier` WHERE codeInsee=' . $next_code_insee . ' ;';
        $res_id_quartier = mysqli_query($conn, $id_quartier);
        while ($row2 = mysqli_fetch_row($res_id_quartier)) {
            $idQ = intval($row2[0]);
            $nomQ = $row2[1];
            $typeQ = $row2[2];
            $departement = intval($row2[3]);
        }

        $print_ = $print_ . '=> Mister X se trouve désormais dans le quartier :"' . $nomQ . '" du type: "' . $typeQ . '", dans le : ' . $departement . '<br><br>';

        # Sauvegrader les tours de Mister
        $req = 'SELECT idRoute, transport FROM `Route` WHERE idQuartierDepart=' . $idQ . ';';
        $res = mysqli_query($conn, $req);
        while ($row = mysqli_fetch_row($res)) {
            $idRoute = $row[0];
            $transport = $row[1];
        }
        $req = 'INSERT INTO `TourMX` (idRoute, nomQ, transport) VALUES ("' . $idRoute . '", "' . $nomQ . '", "' . $transport .'");';
        $res = mysqli_query($conn, $req);

        # Sauvegrader les tours de Mister x FIN

        #2. Déplacer la joueuse

        $req_code_quartier_joueuse = 'SELECT idQ FROM `Quartier` WHERE codeInsee=' . $code_insee_joueuse .';';
        $res_code_quartier_joueuse =  mysqli_query($conn, $req_code_quartier_joueuse);

        while ($row = mysqli_fetch_row($res_code_quartier_joueuse)){
            $code_quartier_joueuse = $row[0];
        }

        $req_next_code_quartier_joueuse = 'SELECT DISTINCT idQuartierArrivee FROM `Route` WHERE idQuartierDepart=' . $code_quartier_joueuse .';';
        $res_next_code_quartier_joueuse =  mysqli_query($conn, $req_next_code_quartier_joueuse);

        while ($row = mysqli_fetch_row($res_next_code_quartier_joueuse)){
            $next_code_quartier_joueuse = $row[0];
        }

        $req_next_code_insee_joueuse = 'SELECT codeInsee FROM `Quartier` WHERE idQ=' . $next_code_quartier_joueuse .';';
        $res_next_code_insee_joueuse =  mysqli_query($conn, $req_next_code_insee_joueuse);

        while ($row = mysqli_fetch_row($res_next_code_insee_joueuse)){
            $next_code_insee_joueuse = $row[0];
        }

        $udpate_misterx_position = 'UPDATE `Joueuse` SET `codeInsee`= '. $next_code_insee_joueuse .' WHERE idPartie=' . $id_partie . ' and mail=' . $mail_joueuse . ';';
        mysqli_query($conn, $udpate_misterx_position);

        $id_quartier = 'SELECT idQ, nomQ, typeQ, departement  FROM `Quartier` WHERE codeInsee=' . $next_code_insee_joueuse . ' ;';
        $res_id_quartier = mysqli_query($conn, $id_quartier);
        while ($row2 = mysqli_fetch_row($res_id_quartier)) {
            $idQ_joueuse = intval($row2[0]);
            $nomQ_joueuse = $row2[1];
            $typeQ_joueuse = $row2[2];
            $departement_joueuse = intval($row2[3]);
        }

        $print_ = $print_ . '=> Vous êtes désormais dans le quartier :"' . $nomQ_joueuse . '" du type: "' . $typeQ_joueuse . '", dans le : ' . $departement_joueuse . '<br><br>';

        if($next_code_insee_joueuse == $next_code_insee){
            $fin_de_la_partie = true;
            $udpate_player_status = 'UPDATE `Joueuse` SET `statutJoueuse`= '. 1 .' WHERE idPartie=' . $id_partie . ' and mail="' . $mail_joueuse . '";';
            mysqli_query($conn, $udpate_player_status);
        }
        else{

            #3. Déplacer les autres detectives
            for ($i = 1; $i <$nbdetective; $i++)
            {

                $code_insee_detective_i =  mysqli_real_escape_string($conn, $_POST['code_insee_detective_' .$i . '']);
                $mail_detective = "detective' . $i . '@gmail.com";
                $req_code_quartier_detective = 'SELECT idQ FROM `Quartier` WHERE codeInsee=' . $code_insee_detective_i .';';
                $res_code_quartier_detective =  mysqli_query($conn, $req_code_quartier_detective);

                while ($row = mysqli_fetch_row($res_code_quartier_detective)){
                    $code_quartier_detective = $row[0];
                }

                $req_next_code_quartier_detective = 'SELECT DISTINCT idQuartierArrivee FROM `Route` WHERE idQuartierDepart=' . $code_quartier_detective .';';
                $res_next_code_quartier_detective =  mysqli_query($conn, $req_next_code_quartier_detective);

                while ($row = mysqli_fetch_row($res_next_code_quartier_detective)){
                    $next_code_quartier_detective = $row[0];
                }

                $req_next_code_insee_detective = 'SELECT codeInsee FROM `Quartier` WHERE idQ=' . $next_code_quartier_detective .';';
                $res_next_code_insee_detective =  mysqli_query($conn, $req_next_code_insee_detective);

                while ($row = mysqli_fetch_row($res_next_code_insee_detective)){
                    $next_code_insee_detective = $row[0];
                }

                $udpate_misterx_position = 'UPDATE `Joueuse` SET `codeInsee`= '. $next_code_insee_detective .' WHERE idPartie=' . $id_partie . ' and mail=' . $mail_detective . ';';
                mysqli_query($conn, $udpate_misterx_position);

                $id_quartier = 'SELECT idQ, nomQ, typeQ, departement  FROM `Quartier` WHERE codeInsee=' . $next_code_insee_detective . ' ;';
                $res_id_quartier = mysqli_query($conn, $id_quartier);
                while ($row2 = mysqli_fetch_row($res_id_quartier)) {
                    $idQ_detective = intval($row2[0]);
                    $nomQ_detective = $row2[1];
                    $typeQ_detective = $row2[2];
                    $departement_detective = intval($row2[3]);
                }

                $tmp_ = $tmp_ . '<input id="code_insee_detective_' . $i . '" name="code_insee_detective_' . $i . '" type="hidden" value='. $next_code_insee_detective .'>';

                $print_ = $print_ . '=> Détective '. $i . ' est désormais dans le quartier :"' . $nomQ_detective . '" du type: "' . $typeQ_detective . '", dans le : ' . $departement_detective . '<br><br>';

                if($next_code_insee_detective == $next_code_insee){
                    $fin_de_la_partie = true;
                    $udpate_player_status = 'UPDATE `Joueuse` SET `statutJoueuse`= '. 1 .' WHERE idPartie=' . $id_partie . ' and mail="' . $mail_joueuse . '";';
                    mysqli_query($conn, $udpate_player_status);
                    break;
                }
            }
        }

        $tour = $tour + 1;

        #4. Vérifier l'état de la partie
        echo $print_;
        if ($tour > 20 && !$fin_de_la_partie){
            echo '=====> FIN DE LA PARTIE. MISTER X A GAGNE"';
            $udpate_player_status = 'UPDATE `Joueuse` SET `statutJoueuse`= '. 1 .' WHERE idPartie=' . $id_partie . ' and mail="' . $mail_joueuse . '";';
            mysqli_query($conn, $udpate_player_status);
            break;
        }
        else if ($tour <= 20 && $fin_de_la_partie){
            echo '=====> FIN DE LA PARTIE. LES DETECTIVES ONT GAGNE"';
            break;
        }
    }

    if (!$fin_de_la_partie && $tour <= 20){
        echo '<form action="game.php" method="post">
            <button type="submit" name="next_step" class="btn btn-info">Prochain coup</button>
            
            <input id="idQ" name="idQ" type="hidden" value='. $idQ .'>
            <input id="mailMX" name="mailMX" type="hidden" value='. $misterXId .'>
            <input id="idPartie" name="idPartie" type="hidden" value='. $id_partie .'>
            
            <input id="code_insee_joueuse" name="code_insee_joueuse" type="hidden" value='. $next_code_insee_joueuse .'>

            <input id="mailJoueuse" name="mailJoueuse" type="hidden" value='. $mail_joueuse .'>
    
            <input id="nbdetective" name="nbdetective" type="hidden" value='. $nbdetective .'>
            
            <input id="tour" name="tour" type="hidden" value='. $tour .'>'

            .  $tmp_ .

            '</form>';
    }

}

?>