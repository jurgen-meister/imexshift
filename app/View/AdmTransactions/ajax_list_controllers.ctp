<?php
//echo $this->BootstrapForm->input('adm_controllers_id', array('id'=>'controllers', 'label'=>'Controladores:', 'name'=>'AdmController[name]' ));
echo $this->BootstrapForm->input('adm_controller_id', array('id'=>'controllers', 'label'=>'Controlador:', 'name'=>'AdmTransaction[adm_controller_id]'
					,'required' => 'required'
					,'helpInline' => '<span class="label label-important">' . __('Requerido') . '</span>&nbsp;')
					);
echo '<br>';
?>
