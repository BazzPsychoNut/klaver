
			<h1>Afspraak plannen</h1>
			
			<div id="afspraak_plannen_head">
				<p>
					Vul hier je beschikbaarheid in voor de komende drie weken.<br/>
					Als tenminste 1 speler van beide teams zijn of haar beschikbaarheid heeft ingevuld, kan een datum geprikt worden.
				</p>
			</div>
			
			<?php if (! empty($feedback)): ?>
		        <?php echo $feedback; ?><br class="clear" />
			<?php endif; ?>
			
			<?php echo $form->render(); ?>
			
			<?php if (! empty($match_id)): ?>
			<h2>Opmerkingen</h2>
			<div id="comments_container">
			</div>
			
			<script type="text/javascript">
			$('comments_container').load('<?php echo APPPATH; ?>klaver/plan/comments/<?php echo $match_id; ?>')
			</script>
			<?php endif; ?>
	