<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
	<head>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

		<title>Scotland Yard</title>

		<link rel="stylesheet" href="/p1716838/project/css/scotland_yard.css">
		<link rel="stylesheet" href="/p1716838/project/css/bootstrap.css">

        <script src="/p1716838/project/js/jquery.js"></script>
        <script src="/p1716838/project/js/popper.js"></script>
        <script src="/p1716838/project/js/bootstrap.js"></script>
        <script src="/p1716838/project/js/datatables.js"></script>
        <script src="/p1716838/project/js/scotland_yard.js"></script>
        <script src="/p1716838/project/js/dataTables.bootstrap4.js"></script>

		
	</head>

	<body id="accueil">
		<?php include('header.php'); ?>
		<?php include('nav_contenu.php'); ?>
		<div id="accueil" class="container-fluid">

            <br>
            <?php include('db_gestion/list_informations.php'); ?>
		</div>
		<?php include('footer.php'); ?>
	</body>
</html>