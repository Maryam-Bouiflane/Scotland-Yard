<?php include('config.php'); ?>
<?php

if (!$conn) { echo 'La connexion a échouée<br>';}
else{
    echo 'Connexion réussie<br>';

    $sql = "CREATE TABLE IF NOT EXISTS Quartier(
            codeInsee   Int NOT NULL ,
            idQ         Int DEFAULT NULL ,
            coords      Varchar (2926) DEFAULT NULL ,
            typeQ       Varchar (1) DEFAULT NULL ,
            nomQ        Varchar (35) DEFAULT NULL ,
            cpCommune   Int DEFAULT NULL ,
            nomCommune  Varchar (35) DEFAULT NULL ,
            departement Int DEFAULT NULL ,
            isQuartierDepart  Int DEFAULT NULL ,
            CONSTRAINT Quartier_PK PRIMARY KEY (codeInsee)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table Quartier a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS Route(
        idRoute           Int Auto_increment  NOT NULL ,
        idQuartierDepart  Int DEFAULT NULL ,
        idQuartierArrivee Int DEFAULT NULL ,
        transport         Varchar (35) DEFAULT NULL ,
        CONSTRAINT Route_PK PRIMARY KEY (idRoute)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table Route a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS Joueuse(
        mail           Varchar (35) NOT NULL ,
        nomJoueuse     Varchar (35) DEFAULT NULL ,
        idPartie       Int NOT NULL ,
        statutJoueuse  Int DEFAULT NULL ,
        codeInsee      Int DEFAULT NULL ,
        CONSTRAINT Joueuse_PK PRIMARY KEY (mail, idPartie),
        CONSTRAINT Joueuse_Quartier_FK FOREIGN KEY (codeInsee) REFERENCES Quartier(codeInsee)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table Joueuse a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS Configuration(
        idConfiguration  Int Auto_increment NOT NULL ,
        nomConfiguration Varchar (35) DEFAULT NULL ,
        dateConf         Varchar (35) DEFAULT NULL ,
        strategie        Varchar (35) DEFAULT NULL ,
        images           Varchar (2926) DEFAULT NULL ,
        CONSTRAINT Configuration_PK PRIMARY KEY (idConfiguration)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table Configuration a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS Partie(
        idPartie                Int Auto_increment NOT NULL ,
        dateDemarrage           Varchar (35) DEFAULT NULL ,
        nbDetectives            Int DEFAULT NULL ,
        idConfiguration         Int DEFAULT NULL ,
        CONSTRAINT Partie_PK PRIMARY KEY (idPartie),
        CONSTRAINT Partie_Configuration_FK FOREIGN KEY (idConfiguration) REFERENCES Configuration(idConfiguration)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table Partie a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS TourMX(
        numTour     Int Auto_increment NOT NULL ,
        idRoute     Int DEFAULT NULL ,
        nomQ        Varchar (35) DEFAULT NULL ,
        transport   Varchar (35) DEFAULT NULL ,
        CONSTRAINT TourMX_PK PRIMARY KEY (numTour),
        CONSTRAINT TourMX_Route_FK FOREIGN KEY (idRoute) REFERENCES Route(idRoute)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table TourMX a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS relie(
        codeInsee Int NOT NULL ,
        idRoute   Int NOT NULL ,
        CONSTRAINT relie_PK PRIMARY KEY (codeInsee,idRoute),
        CONSTRAINT relie_Quartier_FK FOREIGN KEY (codeInsee) REFERENCES Quartier(codeInsee),
        CONSTRAINT relie_Route0_FK FOREIGN KEY (idRoute) REFERENCES Route(idRoute)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table relie a été créée";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS joue(
        idPartie Int NOT NULL ,
        mail     Varchar (35) NOT NULL ,
        CONSTRAINT joue_PK PRIMARY KEY (idPartie,mail),
        CONSTRAINT joue_Partie_FK FOREIGN KEY (idPartie) REFERENCES Partie(idPartie),
        CONSTRAINT joue_Joueuse_FK FOREIGN KEY (mail) REFERENCES Joueuse(mail)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table joue a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE IF NOT EXISTS prend(
        idRoute Int NOT NULL ,
        numTour Int NOT NULL ,
        CONSTRAINT prend_PK PRIMARY KEY (idRoute,numTour),
        CONSTRAINT prend_Route_FK FOREIGN KEY (idRoute) REFERENCES Route(idRoute),
        CONSTRAINT prend_TourMX_FK FOREIGN KEY (numTour) REFERENCES TourMX(numTour)
    )ENGINE=InnoDB;";

    if (mysqli_query($conn, $sql)) {
        echo "La table prend a été créée <br>";
    } else {
        echo "Erreur lors de la création de la table: " . mysqli_error($conn);
    }

}

mysqli_close($conn);

?>
