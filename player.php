<?php
/**
 * Author: lion2486
 * Date: 7/8/2015
 * Website: http://codescar.eu
 */

	require_once('getPlayer.php');
?>
<!doctype HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>title</title>
</head>
<body>
	<h1>Συνεδριάσεις ΔΣ - Live</h1>
	<?php echo get_player(isset($_GET['UID']) ? $_GET['UID'] : "XXXXXXXXX"); ?>
</body>
</html>