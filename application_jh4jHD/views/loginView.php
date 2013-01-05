
			<div id="login_container">
				<h1>Inloggen</h1>
				<?php if (! empty($feedback)): ?>
					<?php echo $feedback; ?><br class="clear" />
				<?php endif; ?>
				<?php if ($this->session->userdata('user_logged_in') && ! $form->isPosted()): ?>
					<p>Je ingelogd als <?php echo $this->session->userdata('user_name'); ?>.</p>
				<?php else: ?>
					<?php echo $form->render(); ?>
				<?php endif; ?>
			</div>
			