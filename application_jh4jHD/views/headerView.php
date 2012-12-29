<?php 
$base_url = $this->config->base_url(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<title>Fonteinkerk Klaverjascompetitie</title>
		
		<link href="<?php echo $base_url; ?>img/favicon.ico" type="image/x-icon" rel="icon" />
		<link href="<?php echo $base_url; ?>img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
		
		<link type="text/css" href="<?php echo $base_url; ?>css/basic.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo $base_url; ?>css/klaver.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo $base_url; ?>css/smoothness/jquery-ui-1.8.24.custom.css" rel="stylesheet" />
		
		<script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-ui-1.8.24.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/klaver.js"></script>
	</head>
	
	<body>
		<div id="header">
			<h1><a href="<?php echo $base_url; ?>">Fonteinkerk Klaverjascompetitie</a></h1>
			<div id="top_menu">
				<ul>
					<!-- I put the <li> directly against the </li> to get rid of the space between menu items -->
					<li><a href="<?php echo $base_url; ?>">Home</a>
					</li><li><a href="<?php echo $base_url; ?>overview">Overzicht</a>
					</li><li><a href="<?php echo $base_url; ?>input_match">Partij invoeren</a>
					</li><li><a href="<?php echo $base_url; ?>plan">Afspraak plannen</a>
					<?php if ($this->session->userdata('user_logged_in') === true): ?>
					</li><li><a href="<?php echo $base_url; ?>account">Mijn gegevens</a>
					</li><li><a href="<?php echo $base_url; ?>logout">Uitloggen</a></li>
					<?php else: ?>
					</li><li><a href="<?php echo $base_url; ?>signup">Aanmelden</a>
					</li><li><a href="<?php echo $base_url; ?>login">Inloggen</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		
		<div id="container">
			<div id="content">
			