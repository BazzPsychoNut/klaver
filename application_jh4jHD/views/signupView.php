
			<div id="signup_container">
				<h1>Aanmelden</h1>
				<p>
					Het is mogelijk je individueel of als team aan te melden.<br/>
					De "Speler 1"-velden zijn verplicht om in te vullen als je je wilt aanmelden. De rest mag leeg blijven.
				</p>
				<?php if (! $form->isValid()): ?>
				<label>&nbsp;</label>
				<div class="invalidations"">
					De aanmelding is mislukt, omdat niet alle velden goed zijn ingevuld.
				</div><br class="clear" />
				<?php endif; ?>
				<?php echo $form->render(); ?>
			</div>
			