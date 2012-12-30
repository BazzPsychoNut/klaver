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
							<h2>Welkom</h2>
							<br/>
							<p>Beste <?php echo $name; ?>,</p>
							<p>
								<?php echo $maatName; ?> heeft je ingeschreven voor de klaverjascompetitie!<br/>
								<?php if (! empty($teamName)): ?>
								Jullie vormen samen het team <?php echo $teamName; ?><br/>
								<?php endif; ?>
							</p>
							<p>
								Klik <a href="<?php echo $activationLink; ?>">hier</a> om je account te activeren.<br/><br/>
								Werkt de link niet? Kopieer dan onderstaande link in je browser.<br/>
								<?php echo $activationLink; ?>
							</p>
							<p>
								Je wachtwoord is Welkom01. Bij de eerste keer inloggen zul een eigen wachtwoord moeten opgeven.
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<h2>Account Gegevens</h2>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<th>Naam</th>
									<td> <?php echo $name; ?></td>
								</tr>
								<tr>
									<th>E-mail</th>
									<td> <?php echo $email; ?></td>
								</tr>
								<tr>
									<th>Wachtwoord</th>
									<td> Welkom01</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<h2>Team</h2>
						</td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<th>Teamnaam</th>
									<td> <?php echo $teamName; ?></td>
								</tr>
								<tr>
									<th>Je maat</th>
									<td> <?php echo $maatName; ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
	
</html>