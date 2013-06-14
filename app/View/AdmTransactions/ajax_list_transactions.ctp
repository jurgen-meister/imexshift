<?php
//echo $this->BootstrapForm->input('adm_action_id', array('type'=>'select', 'multiple'=>'checkbox', 'id'=>'actions', 'name'=>'AdmActionsRole[adm_action_id]','label'=>'Acciones:', 'selected' => $checkedActions ));
/*				
echo $this->BootstrapForm->input('adm_transaction_id', array('id'=>'actions', 'name'=>'AdmAction[sentence]', 'label'=>'Acciones:'
					));
 */
echo $this->BootstrapForm->input('adm_transaction_id', array('type'=>'select', 'multiple'=>'checkbox', 'id'=>'transactions', 'label'=>'Transacciones:', 'selected' => $checkedTransactions ));
?>
