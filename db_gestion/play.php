<?php include('config.php'); ?>

<?php
    if(!isset($_POST['cree'])) {
        echo '<form action="game.php" method="post" id="createJoueuse">
            <div class="form-group">
                <label for="exampleFormControlInput1">Mail de la joueuse</label>
                <input name="email" type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Nombre de détectives</label>
                <select name="nbdetective" class="form-control" id="exampleFormControlSelect1">
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Stratégie</label>
                <select name="strategie" class="form-control" id="exampleFormControlSelect1">
                    <option>Basique</option>
                    <option>Econome</option>
                    <option>Pistage</option>
                </select>
            </div>
            <button type="submit" name="cree" class="btn btn-primary">Créer joueuse</button>
        </form>';
    }
?>

