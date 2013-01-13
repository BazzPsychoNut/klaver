
				<table id="plan_form_table">
					<thead>
						<tr>
							<td></td>
							<?php foreach ($players as $player): ?>
								<th><?php echo $player; ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($dates as $date => $date_fields): ?>
						<tr>
							<th><?php echo $date_fields['day'].'<br/>'.$date_fields['date']; ?></th>
							<?php foreach ($players as $player_id => $player): ?>
							<td>
								<?php echo $player_id == $this->session->userdata('user_id') ? $availabilities[$date]->render() : ''; ?>
								<?php 
								foreach ($images[$player_id][$date] as $img)
									echo $img->render();
								?>
							</td>
							<?php endforeach; ?>
							<td><?php echo $best_options[$date]; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				
				<script type="text/javascript">
				// _g = grey, _d = light, _a = full color
				$('table#plan_form_table img.editable').hover(function() {
					$(this).css({'cursor' : 'pointer'});
					$(this).attr('src', $(this).attr('src').replace('_g', '_d'));
				}, function() {
					$(this).css({'cursor' : 'auto'});
					$(this).attr('src', $(this).attr('src').replace('_d', '_g'));
				});

				var availability_statuses = {'Ja':1, 'Misschien':2, 'Nee':3};  // key = alt of images
				$('table#plan_form_table img.editable').click(function() {
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
				