<?php 
$this->load->helper('form');
?>


			<div id="signup">
				<h1>Aanmelden</h1>
				<p>
					Het is mogelijk je individueel of als team aan te melden.
				</p>
				<?php 
				echo form_open('signup');
				
				// player1
				echo form_input($player1['name']);
				echo form_password($player1['password']);
				echo form_password($player1['password_confirmation']);
				echo form_input($player1['email']);
				
				// player2
				echo form_input($player2['name']);
				echo form_input($player2['email']);
				
				// team
				echo form_input($team['name']);
				
				echo form_close();
				?>
			</div>