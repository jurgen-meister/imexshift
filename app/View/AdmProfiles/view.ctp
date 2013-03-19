<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Adm Profile');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Adm User'); ?></dt>
			<dd>
				<?php echo $this->Html->link($admProfile['AdmUser']['id'], array('controller' => 'adm_users', 'action' => 'view', $admProfile['AdmUser']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('First Name'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['first_name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Last Name'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['last_name']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Birthdate'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['birthdate']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Birthplace'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['birthplace']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Nationality'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['nationality']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Identity Document'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['identity_document']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Place Of Issue'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['place_of_issue']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Address'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['address']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Email'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['email']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Phone'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['phone']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc State'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['lc_state']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Lc Action'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['lc_action']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Creator'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['creator']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Created'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['created']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modifier'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['modifier']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Modified'); ?></dt>
			<dd>
				<?php echo h($admProfile['AdmProfile']['modified']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Adm Profile')), array('action' => 'edit', $admProfile['AdmProfile']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Adm Profile')), array('action' => 'delete', $admProfile['AdmProfile']['id']), null, __('Are you sure you want to delete # %s?', $admProfile['AdmProfile']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Profiles')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm Profile')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Adm Users')), array('controller' => 'adm_users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Adm User')), array('controller' => 'adm_users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

