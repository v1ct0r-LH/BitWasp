          <div class="mainContent span7">
			<div class='row-fluid'>
				<h2>Two Factor Authentication</h2>
				  <?php echo form_open('account/pgp_factor', array('class' => 'form-horizontal')); ?>
				  <fieldset>
					<div class="alert">
	<?php if(isset($returnMessage)) { echo $returnMessage; } else { ?>
To activate two factor authentication, decrypt the following challenge and paste it in the box below:
	<?php } ?>
					</div>
					<div class="control-group">
					<pre class="well span10"><?php echo $challenge;?></pre>
					</div>
					
					<div class="control-group">
					  <label class="control-label" for="answer">Token</label>
					  <div class="controls">
						<input type="text" name='answer' size='12'/>
						<span class="help-inline"><?php echo form_error('answer'); ?></span>
					  </div>
					</div>

					<div class="form-actions">
						<input type="submit" name="submit_pgp_token" value="Continue" />
					  <?php echo anchor('account/two_factor', 'Cancel', 'title="Cancel" class="btn"');?>
					</div>
				  <fieldset>
				</form>
			</div>
		</div>
