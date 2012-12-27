<div class="poule_overview">
	<h2>Wedstrijdoverzicht <?php echo $poulename; ?></h2>
	<?php if (is_array($overview)): ?>
	<table>
		<?php foreach ($overview as $round => $matches): ?>
		<tr>
			<th>
				<?php echo $round; ?>
			</th>
			<?php foreach ($matches as $match): // $match is array(team1 => array(), team2 => array(), score => score) ?>
			<td>
				<table>
					<tr>
						<td class="team">
							<strong><?php echo $match['team1']['name'] ?></strong><br/>
							<?php echo $match['team1']['player1'] ?><br/>
							<?php echo $match['team1']['player2'] ?>
						</td>
						<td>
							<strong>vs</strong>
						</td>
						<td class="team">
						    <strong><?php echo $match['team2']['name'] ?></strong><br/>
							<?php echo $match['team2']['player1'] ?><br/>
							<?php echo $match['team2']['player2'] ?>
						</td>
					</tr>
					<tr>
						<td colspan="3" class="score">
							<?php echo $match['score'] ?>
						</td>
					</tr>
				</table>
			</td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php elseif (is_string($overview)): ?>
		<?php echo $overview; ?>
	<?php endif; ?>
</div>