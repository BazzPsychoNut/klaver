
                    <?php echo $form->render(); ?>
                    
                    <?php if (! empty($comments)): ?>
	                <table>
					    <?php foreach ($comments as $comment): ?>
					    <tr>
							<td><?php echo $comment['name']; ?> op <?php echo $comment['create_date']; ?></td>
						</tr>
						<tr>
							<td><?php echo $comment['comment']; ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
					<?php endif; ?>
	