<?php
echo $this->BootstrapForm->input('adm_controller_id', array('label'=>'Controladores', 'id'=>'controllers', 'name'=>'AdmAction[adm_controller_id]'));
echo '<br>';
echo '<div id="boxActions">';
echo $this->BootstrapForm->input('adm_action_id', array('id'=>'actions', 'name'=>'AdmAction[name]', 'label'=>'Acciones:'
								,'required' => 'required'
								,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
								));
echo '<br>';
echo '</div>';
?>
<?php echo $html;?>