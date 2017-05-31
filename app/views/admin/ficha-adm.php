<script src="<?php echo asset_url("js/vendor/jquery-1.11.1.min.js") ?>"></script>
<?php

$categoria_seleccionada="";
$guardado=0;
if (isset($_POST['submit']) && csrf_filter()) {


	$promociones->nombre = $_POST['nombre'];
	$promociones->marca_id = $_POST['marca_id'];
	$promociones->id_sucursal = $_POST['id_sucursal'];
	$categoria_seleccionada ="0";
	if(isset($_POST['id_categoria'])){
		$categoria_seleccionada = $_POST['id_categoria'][0];
	}
	$promociones->descrip_corta = $_POST['descrip_corta'];
	$promociones->descrip_larga = $_POST['descrip_larga'];
	$promociones->validez_desde = date('Y-m-d H:i:s', strtotime($_POST['validez_fdesde']));
	$promociones->validez_hasta = date('Y-m-d H:i:s',strtotime($_POST['validez_fhasta']));
	$promociones->condiciones = $_POST['condiciones'];
	$promociones->stock = $_POST['stock'];
	$promociones->puntos = $_POST['puntos'];
	$promociones->video = $_POST['video'];
//	$promociones->descuento = $_POST['descuento'];
//	$promociones->puntos_rec = $_POST['puntos_rec'];
	$promociones->status = 1;

	$foto="";
	if (!empty($_FILES['fotos']['name'][0])) {
		$ALLFILLES=$_FILES['fotos']['name'];
		$foto = str_replace("'","",$_FILES['fotos']['name'][0]);
		$foto = str_replace("\"","",$foto);
		$foto = str_replace("%","",$foto );
	}
	
	
		$data = array(
			'marca_id' => $_POST['marca_id'],
	    	'nombre' => $_POST['nombre'],
	    	'id_categoria' => $categoria_seleccionada,
			'descrip_corta' => $_POST['descrip_corta'],
			'descrip_larga' => $_POST['descrip_larga'],
			'fotos'	=> $foto,
	    );
	    $rules = array(
	    	'marca_id' => 'required|numeric|not_in:0',
	    	'nombre' => 'required',
	    	'id_categoria' => 'required|not_in:0',
			'descrip_corta' => 'required',
			'descrip_larga' => 'required',
			'fotos'	=> 'required',
	    );
if (isset($_GET['id'])){
	 $rules = array(
	    	'marca_id' => 'required|numeric|not_in:0',
	    	'nombre' => 'required',
	    	'id_categoria' => 'required|not_in:0',
			'descrip_corta' => 'required',
			'descrip_larga' => 'required',
			
	    );
}

	
  	$validator = Validator::make($data, $rules);
	
	if ($validator->passes()) {

		if ($ver = $promociones->save()) {

			
		 	DB::delete('delete from cat_prod_canje where idproducto = ?', array($promociones->id));

			
			foreach($_POST['id_categoria'] as $idcategoria){
				DB::insert('insert into cat_prod_canje (idproducto,idcategoria) values (?, ?)', array($promociones->id,$idcategoria));
			}

			if (!empty($_FILES['fotos']['name'][0])) {
				DB::update('UPDATE  productos_canje SET fotos=? WHERE  id=?', array(serialize($ALLFILLES),$promociones->id));			
			}
		
			//Crear directorio si no existe
			if(!file_exists(DOCUMENT_ROOT . "/uploads/productos_canje/".$promociones->id."/")){
				mkdir ( DOCUMENT_ROOT . "/uploads/productos_canje/".$promociones->id."/" );
			}

			if( isset( $_POST['eliminar_archivos'] )){
					$eliminar_archivos=$_POST['eliminar_archivos'];
			}

				if(isset( $_POST['eliminar_archivos'] )){
					foreach( $eliminar_archivos as $nombre){
					    unlink('uploads/productos_canje/'.$promociones->id."/".$nombre);
					}
				}

			if (!empty($ALLFILLES)) {
				$key=0;
				foreach($ALLFILLES as $valor){
					$targetFolder = '/uploads/productos_canje/'.$promociones->id."/";
					$name = str_replace("'","",$ALLFILLES[$key]);
					$name = str_replace("\"","",$name);
					$name = str_replace("%","",$name );
					//$tempFile = $_FILES['fotos']['tmp_name'][0]; SI ES MULTIPLE
					$tempFile = $_FILES['fotos']['tmp_name'][$key];
					$targetPath = DOCUMENT_ROOT . $targetFolder;
					//$targetFile = utf8_decode(rtrim($targetPath,'/') . '/' . $name[0] );
					$targetFile = utf8_decode(rtrim($targetPath,'/') . '/' . $name);
					//mkdir(str_replace('//','/',$targetPath), 0777, true);
					move_uploaded_file($tempFile,$targetFile);
					$key++;	
				}
			}
		
			$guardado=1;
			
			redirect_to('?page=canjes', array('canjes_updated' => true));
		} else {
			$errors = new Hazzard\Support\MessageBag(array('error' => trans('errors.dbsave')));
		}
	} else {
		$errors = $validator->errors();
	}
}

?>

<h3 class="page-header">Ficha <Paciente></Paciente></h3>
<p><a href="?page=canjes">Volver a fichas</a></p>
<!-- Modal -->
<div class="modal fade"  id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:950px; height:1000px;">
    <div class="modal-content">
      <div class="modal-body" style="width:910px; height:1000px;">
        <iframe name="myiframe" id="myiframe" src="http://local.marcas.com/promo.php?id=1" style="width:910px; height:1000px;">
        </iframe>
      </div>
      <div class="modal-footer" style="width:910px;">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close">OK Publicar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" id="corregir">Corregir</button>
      </div>
    </div>
  </div>
</div>
<?php if($guardado==0){ ?>
<div class="row">
	<div class="col-md-12">

		<?php if (isset($errors)) {
			echo '<div class="alert alert-danger"><span class="close" data-dismiss="alert">&times;</span><ul>';
			foreach ($errors->all('<li>:message</li>') as $error) {
			   echo $error;
			}
			echo '</ul></div>';
		} ?>

		<form action="" enctype="multipart/form-data" method="POST" >
			<div class="tabtable">
				<!--TABS -->
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab1" id="tab1active" data-toggle="tab">Datos Personales</a>
                    </li>
                    <li>
                        <a href="#tab2" id="tab2active" data-toggle="tab">Localizaci&oacuten</a>
                    </li>
                    <li>
                        <a href="#tab3" id="tab3active" data-toggle="tab">Cl&iacutenicos</a>
                    </li>
			    </ul>

                <?php csrf_input() ?>
				<div class="tab-content">
					<!-- tab 1 -->
					<div class="tab-pane active" id ="tab1">
						<br>
                        <!--abatar-->
                        <?php $user = User::find(Auth::user()->id);?>
                        <div class="avatar-container form-group col-md-4">
                            <label>FOTO</label>
                            <div class="clearfix">
                                <div class="pull-left">
                                    <a href="<?php echo $user->avatar ?>" target="_blank"><img src="<?php echo $user->avatar ?>" class="avatar-image img-thumbnail"></a>
                                </div>
                                <div class="pull-left" style="margin-left: 10px;">
                                    <?php $avatarType = @$user->usermeta['avatar_type']; ?>
                                    <select name="avatar_type" class="form-control">
                                        <option value="" <?php echo $avatarType == '' ? 'selected' : '' ?>><?php _e('main.default') ?></option>
                                        <option value="image" <?php echo $avatarType == 'image' ? 'selected' : '' ?>><?php _e('main.uploaded') ?></option>
                                        <option value="gravatar" <?php echo $avatarType == 'gravatar' ? 'selected' : '' ?>>Gravatar</option>

                                        <?php foreach (Config::get('auth.providers', array()) as $key => $provider) {
                                            if (!empty($user->usermeta["{$key}_id"])) {
                                                echo '<option value="'.$key.'" '.($avatarType == $key ? 'selected' : '').'>'.$provider.'</option>';
                                            }
                                        } ?>
                                    </select>
                                    <div class="btn btn-info btn-sm ip-upload"><?php _e('main.upload') ?> <input type="file" name="file" class="ip-file"></div>
                                    <button type="button" class="btn btn-info btn-sm ip-webcam"><?php _e('main.webcam') ?></button>
                                </div>
                            </div>

                            <div class="alert ip-alert"></div>
                            <div class="ip-info"><?php _e('main.crop_info') ?></div>
                            <div class="ip-preview"></div>
                            <div class="ip-rotate">
                                <button type="button" class="btn btn-default ip-rotate-ccw" title="Rotate counter-clockwise"><span class="icon-ccw"></span></button>
                                <button type="button" class="btn btn-default ip-rotate-cw" title="Rotate clockwise"><span class="icon-cw"></span></button>
                            </div>
                            <div class="ip-progress">
                                <div class="text"><?php _e('main.uploading') ?></div>
                                <div class="progress progress-striped active"><div class="progress-bar"></div></div>
                            </div>
                            <div class="ip-actions">
                                <button type="button" class="btn btn-sm btn-success ip-save"><?php _e('main.save_image') ?></button>
                                <button type="button" class="btn btn-sm btn-primary ip-capture"><?php _e('main.capture') ?></button>
                                <button type="button" class="btn btn-sm btn-default ip-cancel"><?php _e('main.cancel') ?></button>
                            </div>
                        </div>


                        <!-- nombre -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="nombre" id="nombre" value="" class="form-control">
                            </div>
                        </div>
                        <!-- Apellido -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellido">Apellido<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="apellido" id="apellido" value="" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12"></div>
                        <!-- DNI TIPO -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dni_tipo">Tipo DNI</label>
                                <select name="dni_tipo" id="dni_tipo" class="form-control">
                                    <option value="1" tag="1" >DNI </option>
                                    <option value="0" tag="0" >CI </option>
                                    <option value="0" tag="0" >LE </option>
                                </select>
                            </div>
                        </div>
                        <!-- DNI NRO -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="dni_nro">DNI NRO<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="dni_nro" id="dni_nro" value="" class="form-control">
                            </div>
                        </div>
                        <!-- EMAIL -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="Email">Email<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="Email" id="Email" value="" class="form-control">
                            </div>
                        </div>

                        <!-- EDAD -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="Edad">Edad</label>
                                <input type="text" name="Edad" id="Edad" value="" class="form-control">
                            </div>
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label for="Fecha_nac">Fecha Nacimiento</label>
                            <div class='input-group date' id='datetimepickerDesde'>
                                <input type="text" name="Fecha_nac" id="Fecha_nac" value="" class="form-control">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>

                        <!-- ESTADO CIVIL -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="estado_civil">Estado Civil</label>
                                <select name="estado_civil" id="estado_civil" class="form-control">
                                    <option value="Soltero" tag="Soltero" >Soltero </option>
                                    <option value="Casado" tag="Casado" >Casado </option>
                                    <option value="Viudo" tag="Viudo" >Viudo </option>
                                    <option value="Divorciado" tag="Divorciado" >Divorciado </option>
                                </select>
                            </div>
                        </div>

                        <!-- HIJOS -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hijos">Hijos</label>
                                <select name="hijos" id="hijos" class="form-control">
                                    <option value="0" tag="0" >ninguno </option>
                                    <option value="1" tag="1" >1 </option>
                                    <option value="2" tag="2" >2 </option>
                                    <option value="3" tag="3" >3 </option>
                                    <option value="4" tag="4" >4 </option>
                                    <option value="5" tag="5" >5 </option>
                                </select>
                            </div>
                        </div>

                        <!-- SEXO -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sexo">Sexo</label>
                                <select name="sexo" id="sexo" class="form-control">
                                    <option value="0" tag="0" >Seleccione... </option>
                                    <option value="1" tag="1" >Masculino </option>
                                    <option value="2" tag="2" >Femenino </option>
                                </select>
                            </div>
                        </div>

					    <div class="col-md-10">
						 	<div class="form-group">			            	
								<a href="#tab2" data-toggle="tab" class="tomarTab" style="margin-left:450px">Siguiente <span class="glyphicon glyphicon-arrow-right"></span>
								</a>
				            </div>    
			            </div>    

					</div>

					<!--tab 2-->			
					<div class="tab-pane" id="tab2">
						<br>
                        <!-- Calle -->
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="calle">Calle</label>
                                <input type="text" name="calle" id="calle" value="" class="form-control">
                            </div>
                        </div>
                        <!-- Calle nro -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="Calle_nro">Nro</label>
                                <input type="text" name="Calle_nro" id="Calle_nro" value="" class="form-control">
                            </div>
                        </div>
                        <!-- Edificio -->
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="Edificio">Edificio</label>
                                <input type="text" name="Edificio" id="Edificio" value="" class="form-control">
                            </div>
                        </div>
                        <!-- PISO -->
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="Piso">Piso</label>
                                <input type="text" name="Piso" id="Piso" value="" class="form-control">
                            </div>
                        </div>
                        <!-- DPTO -->
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="Dpto">Dpto.</label>
                                <input type="text" name="Dpto" id="Dpto" value="" class="form-control">
                            </div>
                        </div>


                        <!-- CIUDAD -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" name="ciudad" id="ciudad" value="" class="form-control">
                            </div>
                        </div>

                        <!-- LOCALIDAD -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <input type="text" name="localidad" id="localidad" value="" class="form-control">
                            </div>
                        </div>
                        <!-- CP -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cp">CP</label>
                                <input type="text" name="cp" id="cp" value="" class="form-control">
                            </div>
                        </div>


                        <!-- TELEFONO -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input type="text" name="telefono" id="telefono" value="" class="form-control">
                            </div>
                        </div>
                        <!-- FAX -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fax">Fax</label>
                                <input type="text" name="fax" id="fax" value="" class="form-control">
                            </div>
                        </div>
                        <!-- CELULAR -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="text" name="celular" id="celular" value="" class="form-control">
                            </div>
                        </div>
                        <!-- TELEFONO LABORAL -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tel_laboral">Telefono Laboral</label>
                                <input type="text" name="tel_laboral" id="tel_laboral" value="" class="form-control">
                            </div>
                        </div>
  						<br>
                        <div class="col-md-10">
                            <div class="form-group">
                                <a href="#tab3" data-toggle="tab" class="tomarTab" style="margin-left:450px">Siguiente <span class="glyphicon glyphicon-arrow-right"></span>
                                </a>
                            </div>
                        </div>

		        	</div>
                    <!--tab 3-->
                    <div class="tab-pane" id="tab3">
                        <br>
                        <!--OBRA SOCIAL -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_osocial">Obra Social</label>
                                <select name="id_osocial" id="id_osocial" class="form-control">
                                    <option value="">Seleccione... </option>
                                    <?php
                                    $query = DB::select('select * from obrasocial where estado = 1');
                                    foreach ($query as $osociales) {
                                        echo '<option value="'.$osociales->id.'" >'.$osociales->obrasocial.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <!-- PLAN -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="plan">Plan</label>
                                <input type="text" name="plan" id="plan" value="" class="form-control">
                            </div>
                        </div>
                        <!-- PLAN NRO -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="plan_nro">Nro</label>
                                <input type="text" name="plan_nro" id="plan_nro" value="" class="form-control">
                            </div>
                        </div>
                        <!-- PRIMER CONSULTA -->
                        <div class="col-md-4">
                            <label for="fec_pconsulta">Fecha Primer Consulta</label>
                            <div class='input-group date' id='datetimepickerDesde'>
                                <input type="text" name="Fecha_nac" id="fec_pconsulta" value="" class="form-control">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <br>
                            <button type="submit" name="submit" class="btn btn-primary">
                                GUARDAR
                            </button>
                        </div>
                    </div>
			           
				</div>
				
			</div>	
		</form>
		</div>
</div>

    <?php echo '
					    	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
							
							<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
						
								<script>
								$(function () {
								$("#Fecha_nac").datepicker({
									minDate: 0,
								});
                                $("#fec_pconsulta").datepicker({
									minDate: 0,
								});
							
								});
								</script>

							';
    ?>

<?php echo View::make('admin.footer')->render() ?>


<script>

	$(function() {

		$(".tomarTab").click( function(){
			
			$("#tab1active").parent().removeClass("active" );
			$("#tab2active").parent().removeClass("active");
			$("#tab3active").parent().removeClass("active");
			$("#tab4active").parent().removeClass("active");
			if($(this).attr("href")=="#tab1"){
				$("#tab1active").parent().addClass( "active" );
			}
			if($(this).attr("href")=="#tab2"){
				$("#tab2active").parent().addClass( "active" );
			}
			if($(this).attr("href")=="#tab3"){
				$("#tab3active").parent().addClass( "active" );
			}
			if($(this).attr("href")=="#tab4"){
				$("#tab4active").parent().addClass( "active" );
			}
			
			$('html, body').animate({
	           scrollTop: '0px'
	       },
	       1000);
		});

		
		$('[data-toggle="popover"]').popover();
		$('[data-toggle="tab"]').click(function(){
			$('[data-toggle="popover"]').popover('hide');
		});
	   
	    $('.selectpicker').selectpicker({
		    style: 'btn-info',
		    size: 4    
		});

		
		
		/***********************************/
		/*  SELECT DINAMICO 			   */
		/***********************************/		
		var reiniciarSelectDinamico = function(idSelect){
			$('#'+idSelect+' option').each(function(i, option){ 
				$(option).remove(); 			
			});
		}

		//getListadoSucursales
		var ms="";
		var ms2="";
		var ms3="";
		var ms4="";
		$("#marca_id").change(function(){	
			reiniciarSelectDinamico("id_sucursal");
			EasyLogin.admin.getSucursales( $("#marca_id option:selected").val() );
			
		});
	
	
	
	    
	});

</script>
<?php
}
?>