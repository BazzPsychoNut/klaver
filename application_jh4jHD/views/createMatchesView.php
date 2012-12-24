
			<h1>Create Matches</h1>
			
			<div id="feedback">
				<?php echo $feedback; ?>
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
			
	