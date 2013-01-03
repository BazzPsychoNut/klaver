<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<title>Fonteinkerk Klaverjascompetitie</title>
		
		<style type="text/css">
		body            { font-family:Verdana, Arial, sans-serif; font-size:14px; color:#333; background-color:#ddd; }
		p               { margin-bottom:20px; }
		
		/* kopjes */
		h1, h2, h3, h4 	{ font-weight:normal; margin:1em 0 0.5em 0; }
		h1 				{ color:#EE3322; font-size:190%; }
		h2 				{ color:#003D4C; font-size:190%; }
		h3 				{ color:#2C6877; font-size:165%; }
		h4 				{ color:#999933; font-size:100%; }
		
		/* container */
		div#container 	{ border-top:0 none; box-shadow:0 0 5px 5px #888888; margin:0 auto; text-align:left; width:960px; background-color:#fff; padding:0; }
		div#content		{ padding:10px 20px 40px; }
		
		table th		{ padding-right:10px; }
		</style>
	</head>
	
	<body style="font-family:Verdana, Arial, sans-serif; font-size:14px; color:#333; background-color:#ddd;">
		<div id="container" style="border-top:0 none; box-shadow:0 0 5px 5px #888888; margin:0 auto; text-align:left; width:460px; background-color:#fff; padding:0;">
			<div id="content" style="padding:10px 20px 40px;">
				<table style="padding:0; margin:0; border:0;">
					<tr>
						<td>
							<h1>Fonteinkerk Klaverjascompetitie</h1>
						</td>
					</tr>
					<tr>
						<td>	
							<h2>E-mail gewijzigd</h2>
							<br/>
							<p>Beste <?php echo $name; ?>,</p>
							<p>
								Je hebt je e-mail adres gewijzigd. <br/>
								Klik <a href="<?php echo $activationLink; ?>">hier</a> om je account weer te activeren.<br/><br/>
								Werkt de link niet? Kopieer dan onderstaande link in je browser.<br/>
								<?php echo $activationLink; ?>
							</p>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
	
</html>