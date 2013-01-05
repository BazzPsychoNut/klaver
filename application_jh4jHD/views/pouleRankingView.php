<div class="poule_ranking">
	<h2>Stand <?php echo $poulename; ?></h2>
	<?php if (is_array($ranking)): ?>
	<table>
		<thead>
			<tr>
				<?php foreach ($ranking[0] as $key => $value): ?>
				<th><?php echo $key; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($ranking as $row): ?>
			<tr>
				<?php foreach ($row as $value): ?>
				<td style="<?php echo is_numeric($value) ? 'text-align:right' : ''; ?>"><?php echo $value; ?></td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php elseif (is_string($ranking)): ?>
		<?php echo $ranking; ?>
	<?php endif; ?>
</div>