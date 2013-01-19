
				<table id="plan_form_table">
					<thead>
						<tr>
							<td></td>
							<?php foreach ($players as $player): ?>
								<th><?php echo $player; ?></th>
							<?php endforeach; ?>
							<?php if ($date_is_pickable): ?>
							<th>Prik</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($dates as $date => $date_fields): ?>
						<tr id="<?php echo $date ?>" class="<?php echo $date == $previously_picked_date ? 'picked' : ''; ?>">
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
							<?php if ($date_is_pickable): ?>
							<td class="pick"><?php echo (! empty($best_options[$date])) ? $best_options[$date]->render() : ''; ?></td>
							<?php endif; ?>
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
						$(this).attr('src', $(this).attr('src').replace('_a', '_g').replace('_d', '_g'));
					});
				});

				// show "Kies" images that are hidden on mouseover
				$('td.pick').hover(function() {
					$(this).css({'cursor' : 'pointer'});
					$(this).find('img').removeClass('hidden');
				}, function() {
					$(this).css({'cursor' : 'auto'});
					var img = $(this).find('img');
					if (! img.hasClass('keep_alive') && ! img.hasClass('chosen'))
						img.addClass('hidden');
				});

				// pick a date
				$('td.pick img').click(function() {
					// reset previously picked date row
					var img = $('table#plan_form_table tr.picked td.pick img');  // first select img, then remove class ;)
					$('table#plan_form_table tr.picked').removeClass('picked');
					if (img.length > 0)
						img.removeClass('chosen').attr('src', img.attr('src').replace('_a', '_d')).trigger('mouseleave');

					// set clicked date row
					$(this).closest('tr').addClass('picked');
					$(this).attr('src', $(this).attr('src').replace('_d', '_a'));
					$(this).addClass('chosen');

					// set picked date hidden input
					var date = $(this).closest('tr').attr('id');
					$('input#picked_date').val(date);
				});
				
				</script>
				