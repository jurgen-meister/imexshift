<div class="row-fluid">
	<div class="span9">
		<h2>Login</h2>
		<div class="users form">
		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->Form->create('AdmUser'); ?>
			<fieldset>
				<legend><?php echo __('Please enter your username and password'); ?></legend>
				<?php 
				echo $this->Form->input('login');
				echo $this->Form->input('password');
				?>
			</fieldset>
		<?php echo $this->Form->end(__('Login')); ?>
		</div>
	</div>
</div>