
			<h1>Fonteinkerk Klaverjascompetitie</h1>
			
			<div id="welkom">
				<p>
					Inmiddels zijn we bezig met het derde seizoen van de Fonteinkerk Klaverjascompetitie.<br/>
					Ook dit seizoen dreigt weer vol spanning en amusement te zitten.
				</p>
				<p>
					Via deze site kunnen deelnemers de stand bekijken, afspraken plannen en gespeelde partijen invoeren.<br/>
					Veel succes!
				</p>
			</div>
			
			<?php if ($competition_is_started): ?>
			<?php echo $pouleRanking1; ?> 
			<?php echo $pouleRanking2; ?>
			<br class="clear" />
			<?php else: ?>
			<div>
				<p>
					De competitie is nog niet begonnen, dus <a href="<?php echo base_url(); ?>signup">schrijf je nu in</a>!
				</p>
				<p style="font-size:1.2em;">
					Inschrijven kan tot en met <strong>zondag 10 februari</strong>!
				</p>
			</div>
			<?php endif; ?>
	
			<div id="news"></div>