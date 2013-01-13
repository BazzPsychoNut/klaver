
				<table>
					<tr>
						<td id="plan_form_table_players">
							<table id="players">
								<tbody>
									<?php foreach ($players as $player): ?>
									<tr>
										<th><?php echo $player; ?></th>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</td>
						<td>
							<div id="plan_form_table">
								<table>
									<thead>
										<tr>
											<?php foreach ($dates as $date => $date_fields): ?>
											<th><?php echo $date_fields['day'].'<br/>'.$date_fields['date']; ?></th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($players as $player_id => $player): ?>
										<tr>
											<?php foreach ($dates as $date => $date_fields): ?>
											<td>
												<?php echo $availabilities[$player_id][$date]->render(); ?>
												<img alt="Ja" src="<?php echo base_url(); ?>img/yes_g.png" /><img alt="Misschien" src="<?php echo base_url(); ?>img/maybe_g.png" /><img alt="Nee" src="<?php echo base_url(); ?>img/no_g.png" />
											</td>
											<?php endforeach; ?>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				</table>
				<br class="clear" />
				
				<script type="text/javascript">
				// _g = grey, _d = light, _a = full color
				$('div#plan_form_table img').hover(function() {
					$(this).css({'cursor' : 'pointer'});
					var src = $(this).attr('src');
					$(this).attr('src', src.replace('_g', '_d'));
				}, function() {
					$(this).css({'cursor' : 'auto'});
					var src = $(this).attr('src');
					$(this).attr('src', src.replace('_d', '_g'));
				});

				$('div#plan_form_table img').click(function() {
					<?php // TODO set hidden input ?>  
					var src = $(this).attr('src');
					$(this).attr('src', src.replace('_d', '_a'));
					<?php // TODO set the other images back to grey ?>
				});
				</script>