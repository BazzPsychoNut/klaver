
			<h1>Fonteinkerk Klaverjascompetitie</h1>
			
			<div id="welkom">
				<p>
					Inmiddels zijn we bezig met het derde seizoen van de Fonteinkerk Klaverjascompetitie.<br/>
					Ook dit seizoen dreigt weer vol spanning en amusement te zitten.
				</p>
				<p>
					Via deze site kunnen deelnemers de stand bekijken, scores uploaden en afspraken plannen.<br/>
					Veel succes!
				</p>
			</div>
			
			<div id="overzicht">
				<?php foreach ($poules as $poule): ?>
				<table>
					<?php foreach ($poule as $r => $row): ?>
					<tr>
						<?php foreach ($row as $colname => $val): ?>
						<td><?php echo $val; ?></td>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>
				</table>
				<?php endforeach; ?>
			</div>
			
	