
			<h1>Overzicht</h1>
			
			<?php
			if ($competition_is_started)
			{
				foreach ($pouleOverview as $overview)
					echo $overview;
			}
			else
			{
				?>
				<p>Deze mensen hebben zich al ingeschreven.</p>
				<table id="players_overview">
					<thead>
						<tr>
							<th>Speler</th>
							<th>Team</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($players as $row): ?>
						<tr>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['team']; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php 
			} 
			?>
	