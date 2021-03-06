          <div class="mainContent span7">
			<div class='row-fluid'>
				<h2>Two Factor Authentication</h2>
				  <?php echo form_open('login/totp_factor', array('class' => 'form-horizontal')); ?>
				  <fieldset>
					<div class="alert">
	<?php if(isset($returnMessage)) { echo $returnMessage; } else { ?>
					Enter the token as shown on your mobile app to proceed:
	<?php } ?>
					</div>
					
					<div class="control-group">
					  <label class="control-label" for="totp_token">Token</label>
					  <div class="controls">
						<input type="text" name='totp_token' size='12'/>
						<span class="help-inline"><?php echo form_error('totp_token'); ?></span>
					  </div>
					</div>

					<div class="form-actions">
						<input type="submit" class="btn btn-primary "name="submit_totp_token" value="Continue" />
					  <?php echo anchor('logout', 'Cancel', 'title="Cancel" class="btn"');?>
					</div>
				  <fieldset>
				</form>
			</div>
		</div>
