<?php if (!Auth::userCan('manage_roles')) page_restricted();

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
	redirect_to('?page=user-roles');
}

$variable = Variable::find($_GET['id']);

if (isset($_POST['submit']) && csrf_filter()) {

    $data = array(
        'variable'    => $_POST['variable'],
        'valor' => $_POST['valor']
    );

    $rules = array(

        'valor'   => 'required'
    );

    $validator = Validator::make($data, $rules);

	if ($validator->passes()) {
		$variable->valor = $_POST['valor'];

		if ($variable->save()) {
			redirect_to('?page=config-edit&id='.$variable->id, array('variable_updated' => true));
		} else {
			$errors = new Hazzard\Support\MessageBag(array('error' => trans('errors.dbsave')));
		}
	} else {
		$errors = $validator->errors();
	}
}
?>

<?php echo View::make('admin.header')->render() ?>

<h3 class="page-header">Editar La variable: <?php echo $variable->variable ?>    </h3>
<p><a href="?page=config">Volver a Variables</a></p>

<div class="row">
	<div class="col-md-6">
		<?php if ($variable): ?>
			
			<?php if (isset($errors)) {
				echo $errors->first(null, '<div class="alert alert-danger">:message <span class="close" data-dismiss="alert">&times;</span></div>');
			} ?>

			<?php if (Session::has('variable_updated')): Session::deleteFlash(); ?>
				<div class="alert alert-success alert-dismissible">
					<span class="close" data-dismiss="alert">&times;</span>
					Variable Actualizada
				</div>
			<?php endif ?>	
			
			<form action="?page=config-edit&id=<?php echo $variable->id ?>" method="POST">
				<?php csrf_input() ?>
				
				<div class="form-group">
			        <label for="variable">Variable</label>
			        <input type="text" readonly="readonly" name="variable" id="variable" value="<?php echo $variable->variable ?>" class="form-control">
			    </div>
			    
			    <div class="form-group">
			    	<label for="valor">Valor</label> (Para decimales coloque un punto)
                    <input type="text" name="valor" id="valor" value="<?php echo $variable->valor ?>" class="form-control">
			    </div>
			    
			    <div class="form-group">
			    	<button type="submit" name="submit" class="btn btn-primary">Actualizar</button>
			    </div>
			</form>


		<?php else: ?>
			<div class="alert alert-danger">no existe esta variable</div>
		<?php endif; ?>
	</div>
</div>

<?php echo View::make('admin.footer')->render() ?>