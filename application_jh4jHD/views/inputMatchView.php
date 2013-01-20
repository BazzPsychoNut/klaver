
			<h1>Partij invoeren</h1>
			
			<?php if ($competition_is_started): ?>
			
			<p>
				Vul hier een gespeelde partij in.<br/>
				Vul bij punten de volledige, <strong>niet afgeronde</strong> punten in. "NAT" of "PIT" (of "N" of "P") is ook toegestaan.
				<br/><br/>
			</p>
			
			<?php if (! empty($feedback)) echo "<p>$feedback</p>\n"; ?>
			
			<?php echo $form->render(); ?> 
	
			<script type="text/javascript">
			$('table#hands_input input').blur(function() 
			{
				// dont do anything if input is empty
				var value = $(this).val();
				if (! value)
					return false;

				// get details of the blurred (= lost focus) input
				var details = $(this).attr('name').split('_');  // wij_roem_5 -> array(team, type, game)

				// if type = points set the points of the other team
				if (details[1] == 'points')
				{
					// validate input and delete if invalid
					if (isNaN(value) && jQuery.inArray(value.toLowerCase(), ['n', 'nat', 'p', 'pit']) == -1) {
						$(this).val('').after('<div class="invalidations">Ongeldige waarde.</div>');
						return false;
					}
					
					var otherteam = details[0] == 'wij' ? 'zij' : 'wij';
					var otherinput = 'table#hands_input input[name="'+otherteam+'_points_'+details[2]+'"]';
					var points = isNaN(value) ? 0 : parseFloat(value);  // PIT or NAT are 0 points

					// dont overwrite NAT or PIT with calculated 0
					if (! (points == 162 && isNaN($(otherinput).val())) )
					    $(otherinput).val(162 - points);

				    // set 0 roem when nat or pit is entered
				    if (isNaN(value))
					    $('table#hands_input input[name="'+details[0]+'_roem_'+details[2]+'"]').val("0");
				}
				else  // roem is entered
				{
					// validate roem
					if (isNaN(value)) {
						$(this).val('').after('<div class="invalidations">Ongeldige waarde.</div>');
						return false;
					}
				}

				// update total of wij and zij
				// This is not the most efficient, but you won't notice the performance difference between only updating when needed and this.
				update_total('wij', details[1]);
				update_total('zij', details[1]);

				// remove invalidations if there is one
				$(this).next('div.invalidations').remove();
			});

			function update_total(team, type)
			{
				var total_id = 'td#total_' + type + '_' + team;
				var sum = 0;
		        //iterate through each input and add the values
		        $('table#hands_input input[name^="'+ team + '_' + type+'"]').each(function() 
		        {
		            //add only if the value is number
		            var val = $(this).val();
		            if(! isNaN(val) && val.length != 0) {
		                sum += parseFloat(val);
		            }
		        });
		        // replace td content with sum
				$(total_id).html(sum);
			}
			</script>
				
			<?php else: // competition_is_started ?>
			
			<p>De competitie is nog niet begonnen, dus er kunnen nog geen gespeelde partijen worden ingevoerd.</p>
			
			<?php endif; ?>
			