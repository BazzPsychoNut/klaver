
			<div id="place_team_container" class="account_form">
				<h2>Teams indelen</h2>
				<?php if (! empty($feedback) && $form->isPosted()): ?>
				<?php echo $feedback; ?><br class="clear" />
				<?php endif; ?>
				<?php echo $form->render(); ?>
			</div>
			