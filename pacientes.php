<?php
require_once 'app/init.php';

if (Auth::guest()) redirect_to(App::url());

$page = isset($_GET['p']) ? $_GET['p'] : 'account';
?>

<?php echo View::make('header')->render() ?>

<div class="row">
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
            <li <?php echo $page == 'ficha' ? 'class="active"':'' ?>><a href="?p=ficha">Ficha paciente</a></li>
            <li <?php echo $page == 'tratamientos' ? 'class="active"':'' ?>><a href="?p=pacientes">listar</a></li>
			<li <?php echo $page == 'consultas' ? 'class="active"':'' ?>><a href="?p=consultas">Consultas</a></li>
			<li <?php echo $page == 'presupuestos' ? 'class="active"':'' ?>><a href="?p=presupuestos">Presupuestos</a></li>
		</ul>
	</div>
	<div class="col-md-10">
<?php
switch ($page) {

	// Account
	case 'account':
		?>		
		<h3 class="page-header"><?php echo _e('main.account') ?></h3>
		<form action="settingsAccount" method="POST" class="ajax-form">
			<?php if (Config::get('auth.require_username') && Config::get('auth.username_change')): ?>
				<div class="form-group">
			        <label for="username"><?php _e('main.username') ?> <em><?php _e('main.required') ?></em></label>
			        <input type="text" name="username" id="username" value="<?php echo Auth::user()->username ?>" class="form-control">
			    </div>
			<?php endif ?>

			<div class="form-group">
		        <label for="email"><?php _e('main.email') ?> <em><?php _e('main.required') ?></em></label>
		        <input type="text" name="email" id="email" value="<?php echo Auth::user()->email ?>" class="form-control">
		    </div>

		 <!--   <div class="form-group">
		        <label for="locale"><?php _e('main.language') ?></label>
		        <select name="locale" id="locale" class="form-control">
		        <?php $locales = Config::get('app.locales'); ?>
	        	<?php //foreach ($locales as $key => $lang) : ?>
					<option value="<?php //echo $key ?>" <?php //echo Auth::user()->locale == $key ? 'selected' : '' ?>><?php //echo $lang ?></option>
				<?php //endforeach ?>
				</select>
		    </div>-->

            <div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.save_changes') ?></button>
		    	<?php if (Config::get('auth.delete_account')): ?>
					<div class="pull-right v-middle"><a href="?p=delete"><?php _e('main.delete_my_account') ?></a></div>
				<?php endif ?>
		    </div>
		</form>
		<?php
	break;

	// Password
	case 'password':
		?>
		<h3 class="page-header"><?php echo _e('main.password') ?></h3>
		<form action="settingsPassword" method="POST" class="ajax-form">
			<div class="form-group">
		        <label for="pass1"><?php _e('main.current_password') ?></label>
		        <input type="password" name="pass1" id="pass1" class="form-control" autocomplete="off" value="">
		    </div>
			<div class="form-group">
		        <label for="pass2"><?php _e('main.newpassword') ?></label>
		        <input type="password" name="pass2" id="pass2" class="form-control" autocomplete="off" value="">
		    </div>
		    <div class="form-group">
		        <label for="pass3"><?php _e('main.newpassword_confirmation') ?></label>
		        <input type="password" name="pass3" id="pass3" class="form-control" autocomplete="off" value="">
			</div>
			<div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-primary"><?php _e('main.save_changes') ?></button>
		    </div>
		</form>
		<?php
	break;


	// Profile
	case 'ficha':
		$user = User::find(Auth::user()->id);
		?>
		<link href="<?php echo asset_url('css/vendor/imgpicker.css') ?>" rel="stylesheet">
        <?php echo View::make('admin.ficha-adm')->render() ?>

		<?php
	break;

	// Messages
	case 'pacientes':
		?>
        <?php echo View::make('admin.pacientes')->render() ?>


        <?php
	break;

	// Connect
	case 'connect':
		?>
		<div class="row">
			<div class="col-md-6 social-icons">
				<?php foreach (Config::get('auth.providers', array()) as $key => $provider) {
					?>
					<ul class="list-group">
						<li class="list-group-item clearfix">
							<span class="icon-<?php echo $key ?>"></span> <?php echo $provider ?>
							<?php if (empty(Auth::user()->usermeta["{$key}_id"])): ?>
								<a href="oauth.php?provider=<?php echo $key ?>" class="btn btn-info btn-sm pull-right"><?php _e('main.connect') ?></a>
							<?php else: ?>
								<a href="oauth.php?provider=<?php echo $key ?>&disconnect=1" class="btn btn-danger btn-sm pull-right"><?php _e('main.disconnect') ?></a>
							<?php endif ?>
						</li>
					</ul>
					<?php
				} ?>
			</div>
		</div>
		<p>
			<span class="label label-warning"><?php _e('main.warning') ?></span>
			<?php _e('main.connect_warning', array('password' => '<a href="?p=password">'.trans('main.password').'</a>')) ?>
		</p>
		<?php
	break;

	// Delete account
	case 'delete':
		if (!Config::get('auth.delete_account')) {
			redirect_to('?p=account');
		}

		if (isset($_POST['submit']) && csrf_filter()) {
			$id = Auth::user()->id;

			User::where('id', $id)->limit(1)->delete();
			
			Usermeta::delete($id);

			Message::newQuery()->where('to_user', $id)
							   ->orWhere('from_user', $id)
							   ->delete();
			
			Contact::deleteAll($id);

			Comments::deleteUserComments($id);
			
			Auth::logout();
			
			redirect_to(App::url());
		}
		?>
		<h3 class="page-header"><?php echo _e('main.delete_account') ?></h3>
		<?php _e('main.delete_account_message') ?>
		<form action="" method="POST">
			<?php csrf_input() ?>
			<div class="form-group">
		    	<button type="submit" name="submit" class="btn btn-danger"><?php _e('main.delete_my_account_confirm') ?></button>
		    </div>
		</form>
		<?php
	break;

	default:
		redirect_to('?p=account');
	break;
}
?>
	</div>
</div>

<?php echo View::make('footer')->render() ?>