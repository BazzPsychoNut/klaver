
			<div id="change_account_container" class="account_form">
				<h2>Accountgegevens wijzigen</h2>
				<?php if (! empty($feedback) && $form->isPosted()): ?>
				<?php echo $feedback; ?><br class="clear" />
				<?php endif; ?>
				<?php echo $form->render(); ?>
			</div>
			