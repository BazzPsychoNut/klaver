
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
												<img alt="Ja" title="Ja" src="<?php echo base_url(); ?>img/yes_g.png" /><img alt="Misschien" title="Misschien" src="<?php echo base_url(); ?>img/maybe_g.png" /><img alt="Nee" title="Nee" src="<?php echo base_url(); ?>img/no_g.png" />
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
					$(this).attr('src', $(this).attr('src').replace('_g', '_d'));
				}, function() {
					$(this).css({'cursor' : 'auto'});
					$(this).attr('src', $(this).attr('src').replace('_d', '_g'));
				});

				var availability_statuses = {'Ja':1, 'Misschien':2, 'Nee':3};  // key = alt of images
				$('div#plan_form_table img').click(function() {
					// set hidden input field  
					$(this).prevAll('input').val(availability_statuses[$(this).attr('alt')]);
					
					// set img versions
					$(this).attr('src', $(this).attr('src').replace('_d', '_a'));

					// set the other images back to grey
					$(this).siblings('img').each(function() {
						$(this).attr('src', $(this).attr('src').replace('_a', '_g'));
					});
				});
				</script>
				