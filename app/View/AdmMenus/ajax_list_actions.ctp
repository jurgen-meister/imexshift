<?php
				echo $this->BootstrapForm->input('adm_action_id', array('default'=>0, 'label'=>'Control->Acción'
				,'required' => 'required'
				,'name'=>'AdmMenu[adm_action_id]'	
				,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'	
					));
				echo '<br>';
				echo $this->BootstrapForm->input('adm_menu_id', array('label'=>'Padre', 'default'=>0
				,'required' => 'required'
				,'name'=>'AdmMenu[parent_node]'	
				,'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;'	
				));
