<?php echo View::make('admin.header')->render() ?>
<?php if (!Auth::userCan('8')) page_restricted(); 

if (isset($_POST['submit']) && csrf_filter()) {

	$data = array(
    	'id_padre' => $_POST['id_padre'],
    	'nombre' => $_POST['nombre'],    		
    );

	$rules = array(
    	'id_padre' => 'required',
    	'nombre'   => 'required',
    );
	

  	$validator = Validator::make($data, $rules);
	
	if ($validator->passes()) {
		$esp = new Especialidades;

		$esp->id_padre = $_POST['id_padre'];
		$esp->nombre = $_POST['nombre'];
		
		if ($esp->save()) {
			redirect_to('?page=especialidades', array('especialidad_updated' => true));
		} else {
			$errors = new Hazzard\Support\MessageBag(array('error' => trans('errors.dbsave')));
		}
	} else {
		$errors = $validator->errors();
	}
}
?>


<h3 class="page-header">Agregue una Especialidad</h3>
<div class="row">
	<div class="col-md-8">

		<?php if (isset($errors)) {
			echo '<div class="alert alert-danger"><span class="close" data-dismiss="alert">&times;</span><ul>';
			foreach ($errors->all('<li>:message</li>') as $error) {
			   echo $error;
			}
			echo '</ul></div>';
		} ?>

		<form action="" method="POST">
			<?php csrf_input() ?>
			

				<div class="form-group">
			        <label for="id_padre">Id Especialidad Padre <?php _e('admin.required') ?></em></label>
			        <input type="text" name="id_padre" id="id_padre" value="0" class="form-control">
			    </div>

			    <div class="form-group">
			        <label for="nombre">Especialidad <?php _e('admin.required') ?></em></label>
			        <input type="text" name="nombre" id="nombre" class="form-control">
			    </div>

            <br>
            <div class="form-group">
				<button type="submit" name="submit" class="btn btn-primary">Agregar </button>
			</div>
		</form>
	</div>
</div>

<?php echo View::make('admin.footer')->render() ?>