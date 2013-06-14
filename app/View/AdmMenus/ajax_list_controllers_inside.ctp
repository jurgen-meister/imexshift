<?php
echo $this->BootstrapForm->input('adm_controller_id', array('label'=>'Controladores', 'id'=>'controllers', 'name'=>'AdmMenu[adm_controller_id]'));
echo '<div id="boxActions">';
echo $this->BootstrapForm->input('adm_action_id', array('id'=>'actions', 'name'=>'AdmMenu[adm_action_id]', 'label'=>'Acciones:'
								,'required' => 'required'
								,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'
								));
echo '</div>';
?>