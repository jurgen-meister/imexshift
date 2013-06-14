<?php //debug($this->session->read('User.chooserole'));
echo '<p>El "Rol:'.$this->session->read('RoleInactive.name').'" con la "Gestion:'.$this->session->read('PeriodInactive.name').'" de este Usuario esta INACTIVO, comuniquese con su administrador. Sin embargo puede ingresar al sistema con los siguientes roles que tiene activos:</p>';
 foreach ($this->session->read('User.chooserole') as $value) {
	 $options[$value['AdmUserRestriction']['id'].'-'.$value['AdmUser']['id'].'-'.$value['AdmUser']['login']]='Rol: '.$value['AdmRole']['name'].' | Gestion: '.$value['AdmUserRestriction']['period']; 
}
echo $this->BootstrapForm->create('AdmUser', array('class' => 'form-horizontal'));
$attributes = array('legend' => false, 'required' => 'required');
echo $this->BootstrapForm->radio('userAccountSession', $options, $attributes);

echo '<div class="form-actions" >';
		echo $this->BootstrapForm->submit('Ingresar al sistema',array('class'=>'btn btn-primary','div'=>false, 'id'=>'btnSaveAll'));
echo '</div>';
echo $this->BootstrapForm->end();