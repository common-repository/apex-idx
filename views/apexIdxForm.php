<form method="post" action="options.php" id="apexApiForm" name="apexApiForm">
	<div class="custom-box">
		<?php wp_nonce_field('update-options'); ?>
		
		<div class="apexApi">
			<label for="apexAgentApiKey">Please enter your API key to continue: </label>
			<input name="apexAgentApiKey" type="text" id="apexAgentApiKey" value="<?php echo get_option('apexAgentApiKey'); ?>" />
		</div>
		
		<input type="submit" value="<?php esc_html_e('Save Changes') ?>" id="saveApexApi" class="button-primary"  />
	
		<?php
			if($apexApiValidationError){
				echo '<p class="apexApiError" id="apexApiError">'.$apexApiValidationError.'</p>';
			}
			if($apexApiValidationSuc) {
				echo '<p class="apexApiSuc" id="apexApiSuc">'.$apexApiValidationSuc.'</p>';
			}
		?>
		
		
	</div>
	<?php settings_fields('apexIdx-settings-group'); ?>
</form>