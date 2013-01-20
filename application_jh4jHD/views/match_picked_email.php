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
		
		table#partij_gegevens th    { font-weight:bold; }
		table#partij_gegevens th, 
		table#partij_gegevens td    { padding:4px 10px; }
		</style>
	</head>
	
	<body style="font-family:Verdana, Arial, sans-serif; font-size:14px; color:#333; background-color:#ddd;">
		<div id="container" style="border-top:0 none; box-shadow:0 0 5px 5px #888888; margin:0 auto; text-align:left; width:460px; background-color:#fff; padding:0;">
			<div id="content" style="padding:10px 20px 40px;">
				<table style="padding:0; margin:0; border:0;">
					<tr>
						<td>
							<h1 style="font-weight:normal; margin:1em 0 0.5em 0; color:#EE3322; font-size:190%;">Fonteinkerk Klaverjascompetitie</h1>
						</td>
					</tr>
					<tr>
						<td>	
							<h2 style="font-weight:normal; margin:1em 0 0.5em 0; color:#003D4C; font-size:190%;">Afspraak geprikt</h2>
							<br/>
							<p>Beste <?php echo $name; ?>,</p>
							<p>
							    <?php echo ucfirst($pick_user_name); ?> heeft een datum geprikt voor de partij tussen 
							    team <?php echo $teams[1]['name']; ?> en team <?php echo $teams[2]['name']; ?>
							    <br/><br/>
							    <strong style="font-weight:bold; font-size:24px; margin:40px;"><?php echo $picked_date; ?></strong>
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<h2 style="font-weight:normal; margin:1em 0 0.5em 0; color:#003D4C; font-size:190%;">Team Gegevens</h2>
						</td>
					</tr>
					<tr>
						<td>
							<table id="partij_gegevens">
							    <thead>
    								<tr>
    								    <td></td>
    									<th><?php echo $teams[1]['name']; ?></th>
    									<th><?php echo $teams[2]['name']; ?></th>
    								</tr>
							    </thead>
							    <tbody>
    							    <tr>
    							        <th>Spelers</th>
    									<td><?php echo implode('<br/>', $teams[1]['players']); ?></td>
    									<td><?php echo implode('<br/>', $teams[2]['players']); ?></td>
    								</tr>
    							    <tr>
    							        <th>Score</th>
    									<td><?php echo $teams[1]['wins']; ?> gewonnen<br/><?php echo $teams[1]['losses']; ?> verloren</td>
    									<td><?php echo $teams[2]['wins']; ?> gewonnen<br/><?php echo $teams[2]['losses']; ?> verloren</td>
    								</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<?php if (! empty($comments)): ?>
					<tr>
						<td>
							<h2 style="font-weight:normal; margin:1em 0 0.5em 0; color:#003D4C; font-size:190%;">Opmerkingen</h2>
						</td>
					</tr>
					<tr>
						<td>
							<table>
							    <?php foreach ($comments as $comment): ?>
							    <tr>
									<td><?php echo $comment['name']; ?> op <?php echo $comment['create_date']; ?></td>
								</tr>
								<tr>
									<td><?php echo $comment['comment']; ?></td>
								</tr>
								<?php endforeach; ?>
							</table>
						</td>
					</tr>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</body>
	
</html>