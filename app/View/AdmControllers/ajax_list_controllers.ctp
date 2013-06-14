<?php
//echo $this->BootstrapForm->input('adm_controllers_id', array('id'=>'controllers', 'label'=>'Controladores:', 'name'=>'AdmController[name]' ));
echo $this->BootstrapForm->input('adm_controllers_id', array('id'=>'controllers', 'label'=>'Controladores:', 'name'=>'AdmController[name]' 
					,'required' => 'required'
					,'helpInline' => '<span class="label label-important">' . __('Obligatorio') . '</span>&nbsp;')
					);
//echo '<br>';
?>
