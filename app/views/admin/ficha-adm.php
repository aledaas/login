<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<?php

if (isset($_GET['id'])){
    $paciente = Pacientes::find($_GET['id']);
}else{
    $paciente = new Pacientes;
}

$guardado=0;
if (isset($_POST['submit']) && csrf_filter()) {

	$paciente->nroficha = null;
	$paciente->avatar = "";
	$paciente->nombre = $_POST['nombre'];
	$paciente->apellido = $_POST['apellido'];
	$paciente->dni_tipo = $_POST['dni_tipo'];
	$paciente->dni_nro = $_POST['dni_nro'];
	$paciente->email = $_POST['email'];
	$paciente->edad = $_POST['edad'];
	$paciente->fecha_nac = date('Y-m-d H:i:s', strtotime($_POST['fecha_nac']));
	$paciente->est_civil = $_POST['est_civil'];
	$paciente->hijos = $_POST['hijos'];
	$paciente->sexo = $_POST['sexo'];
	$paciente->calle = $_POST['calle'];
	$paciente->calle_nro = $_POST['calle_nro'];
	$paciente->edificio = $_POST['edificio'];
	$paciente->piso = $_POST['piso'];
	$paciente->dpto = $_POST['dpto'];
	$paciente->ciudad = $_POST['ciudad'];
	$paciente->localidad = $_POST['localidad'];
	$paciente->cp = $_POST['cp'];
	$paciente->telefono = $_POST['telefono'];
	$paciente->fax = $_POST['fax'];
	$paciente->celular = $_POST['celular'];
	$paciente->tel_laboral = $_POST['tel_laboral'];
	$paciente->obra_social = $_POST['obra_social'];
	$paciente->plan = $_POST['plan'];
	$paciente->plan_nro = $_POST['plan_nro'];
	$paciente->fec_pconsulta = date('Y-m-d', strtotime($_POST['fec_pconsulta']));
//	$paciente->id_especialidad = $_POST['id_especialidad'];

	$foto="";
	if (!empty($_FILES['fotos']['name'][0])) {
		$ALLFILLES=$_FILES['fotos']['name'];
		$foto = str_replace("'","",$_FILES['fotos']['name'][0]);
		$foto = str_replace("\"","",$foto);
		$foto = str_replace("%","",$foto );
	}

    $data = array(
        'id' => null,
        'nroficha' => null,
        'avatar' => null,
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'dni_tipo' => $_POST['dni_tipo'],
        'dni_nro' => $_POST['dni_nro'],
        'email' => $_POST['email'],
        'edad' => $_POST['edad'],
        'fecha_nac' => date('Y-m-d H:i:s', strtotime($_POST['fecha_nac'])),
        'est_civil' => $_POST['est_civil'],
        'hijos' => $_POST['hijos'],
        'sexo' => $_POST['sexo'],
        'calle' => $_POST['calle'],
        'calle_nro' => $_POST['calle_nro'],
        'edificio' => $_POST['edificio'],
        'piso' => $_POST['piso'],
        'dpto' => $_POST['dpto'],
        'ciudad' => $_POST['ciudad'],
        'localidad' => $_POST['localidad'],
        'cp' => $_POST['cp'],
        'telefono' => $_POST['telefono'],
        'fax' => $_POST['fax'],
        'celular' => $_POST['celular'],
        'tel_laboral' => $_POST['tel_laboral'],
        'obra_social' => $_POST['obra_social'],
        'plan' => $_POST['plan'],
        'plan_nro' => $_POST['plan_nro'],
        'fec_pconsulta' => date('Y-m-d', strtotime($_POST['fec_pconsulta'])),
      //  'id_especialidad' => $_POST['id_especialidad'],
    );
    $rules = array(
        'nombre' => 'required',
        'apellido' => 'required',
        'fec_pconsulta' => 'required|not_in:0',
       // 'id_especialidad'=> 'required',
    );

  	$validator = Validator::make($data, $rules);
	
	if ($validator->passes()) {

		if ($ver = $paciente->save()) {

		
			$guardado=1;
			
			redirect_to('?p=pacientes', array('canjes_updated' => true));
		} else {
			$errors = new Hazzard\Support\MessageBag(array('error' => trans('errors.dbsave')));
		}
	} else {
		$errors = $validator->errors();
	}
}

?>

<h3 class="page-header">Ficha <Paciente></Paciente></h3>
<p><a href="?p=pacientes">Volver a fichas</a></p>



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

                        <!-- PRIMER CONSULTA -->
                        <div class="col-md-4">
                            <label for="fec_pconsulta">Fecha Primer Consulta</label>
                            <div class='input-group date' id='dateconsulta'>
                                <input type="text" name="fec_pconsulta" id="fec_pconsulta" value="<?php if(isset($paciente->fec_pconsulta)){ echo $paciente->fec_pconsulta; }else{ echo date("Y-m-d"); } ?>" class="form-control">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#dateconsulta').datetimepicker({
                                        // viewMode: 'years',
                                        format: 'YYYY-MM-DD'
                                    });
                                });
                            </script>
                        </div>
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

                        <!-- Apellido -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellido">Apellido<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="apellido" id="apellido" value="<?php echo $paciente->apellido ?>" class="form-control">
                            </div>
                        </div>
                        <!-- nombre -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="nombre" id="nombre" value="<?php echo $paciente->nombre ?>" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12"></div>
                        <!-- DNI TIPO -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dni_tipo">Tipo DNI</label>
                                <select name="dni_tipo" id="dni_tipo" class="form-control">
                                    <option value="DNI" tag="DNI" >DNI </option>
                                    <option value="CI" tag="CI" >CI </option>
                                    <option value="LE" tag="LE" >LE </option>
                                </select>
                            </div>
                        </div>
                        <!-- DNI NRO -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dni_nro">DNI NRO<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="dni_nro" id="dni_nro" value="<?php echo $paciente->dni_nro ?>" class="form-control">
                            </div>
                        </div>
                        <!-- CELULAR -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="text" name="celular" id="celular" value="<?php echo $paciente->celular ?>" class="form-control">
                            </div>
                        </div>
                        <!-- EMAIL -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="email">Email<em><?php _e('admin.required') ?></em></label>
                                <input type="text" name="email" id="email" value="<?php echo $paciente->email ?>" class="form-control">
                            </div>
                        </div>

                        <!-- EDAD -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="edad">Edad</label>
                                <input type="text" name="edad" id="edad" value="<?php echo $paciente->edad ?>" class="form-control">
                            </div>
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-md-4">
                            <label for="fecha_nac">Fecha Nacimiento</label>
                            <div class='input-group date' id='datetimepickerDesde'>
                                <input type="text" name="fecha_nac" id="fecha_nac" value="<?php echo $paciente->fecha_nac ?>" class="form-control">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepickerDesde').datetimepicker({
                                    viewMode: 'years',
                                    format: 'YYYY-MM-DD'
                                });
                            });
                        </script>


                        <!-- ESTADO CIVIL -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="est_civil">Estado Civil</label>
                                <select name="est_civil" id="est_civil" class="form-control">
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
                                    <option value="masc" tag="1" >Masculino </option>
                                    <option value="feme" tag="2" >Femenino </option>
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
                                <input type="text" name="calle" id="calle" value="<?php echo $paciente->calle ?>" class="form-control">
                            </div>
                        </div>
                        <!-- Calle nro -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="calle_nro">Nro</label>
                                <input type="text" name="calle_nro" id="calle_nro" value="<?php echo $paciente->calle_nro ?>" class="form-control">
                            </div>
                        </div>
                        <!-- Edificio -->
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="edificio">Edificio</label>
                                <input type="text" name="edificio" id="edificio" value="<?php echo $paciente->edificio ?>" class="form-control">
                            </div>
                        </div>
                        <!-- PISO -->
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="piso">Piso</label>
                                <input type="text" name="piso" id="piso" value="<?php echo $paciente->piso ?>" class="form-control">
                            </div>
                        </div>
                        <!-- DPTO -->
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="dpto">Dpto.</label>
                                <input type="text" name="dpto" id="dpto" value="<?php echo $paciente->dpto ?>" class="form-control">
                            </div>
                        </div>


                        <!-- CIUDAD -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" name="ciudad" id="ciudad" value="<?php echo $paciente->ciudad ?>" class="form-control">
                            </div>
                        </div>

                        <!-- LOCALIDAD -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <input type="text" name="localidad" id="localidad" value="<?php echo $paciente->localidad ?>" class="form-control">
                            </div>
                        </div>
                        <!-- CP -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cp">CP</label>
                                <input type="text" name="cp" id="cp" value="<?php echo $paciente->cp ?>" class="form-control">
                            </div>
                        </div>


                        <!-- TELEFONO -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input type="text" name="telefono" id="telefono" value="<?php echo $paciente->telefono ?>" class="form-control">
                            </div>
                        </div>
                        <!-- FAX -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fax">Fax</label>
                                <input type="text" name="fax" id="fax" value="<?php echo $paciente->fax ?>" class="form-control">
                            </div>
                        </div>

                        <!-- TELEFONO LABORAL -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tel_laboral">Telefono Laboral</label>
                                <input type="text" name="tel_laboral" id="tel_laboral" value="<?php echo $paciente->tel_laboral ?>" class="form-control">
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
                                <label for="obra_social">Obra Social</label>
                                <select name="obra_social" id="obra_social" class="form-control">
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
                                <input type="text" name="plan" id="plan" value="<?php echo $paciente->plan ?>" class="form-control">
                            </div>
                        </div>
                        <!-- PLAN NRO -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="plan_nro">Nro</label>
                                <input type="text" name="plan_nro" id="plan_nro" value="<?php echo $paciente->plan_nro ?>" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_especialidad">Asignar a </label>
                              <!--  <select name="id_especialidad[]" id="id_especialidad" class="selectpicker" multiple data-selected-text-format="count>6" data-width="100%"> -->
                                    <select name="id_especialidad[]" id="id_especialidad" class="form-control">
                                    <?php
                                    foreach ((array) Especialidades::all() as $especialidades) {
                                       /* $selected=0;
                                        if($especialidad_seleccionada!="vacio"){
                                            $query = DB::select('select idcategoria from cat_prod_canje as cp where cp.idproducto = ?', array($promociones->id));
                                            foreach($query as $categoria){
                                                if($categoria->idcategoria==$categorias->id){
                                                    $selected=1;
                                                }
                                            }
                                        } */

                                        echo '<option value="'.$especialidades->id.'">'.$especialidades->nombre.'</option>';

                                    }
                                    ?>
                                </select>
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

        $("#fecha_nac").blur(function(){
            var value = calcularEdad($(this).val());
            $("#edad").val(value);

        });
	
	    
	});

</script>

    <script type="text/javascript">
        /**
         * Funcion que devuelve true o false dependiendo de si la fecha es correcta.
         * Tiene que recibir el dia, mes y año
         */
        function isValidDate(day,month,year)
        {
            var dteDate;

            // En javascript, el mes empieza en la posicion 0 y termina en la 11
            //   siendo 0 el mes de enero
            // Por esta razon, tenemos que restar 1 al mes
            month=month-1;
            // Establecemos un objeto Data con los valore recibidos
            // Los parametros son: año, mes, dia, hora, minuto y segundos
            // getDate(); devuelve el dia como un entero entre 1 y 31
            // getDay(); devuelve un num del 0 al 6 indicando siel dia es lunes,
            //   martes, miercoles ...
            // getHours(); Devuelve la hora
            // getMinutes(); Devuelve los minutos
            // getMonth(); devuelve el mes como un numero de 0 a 11
            // getTime(); Devuelve el tiempo transcurrido en milisegundos desde el 1
            //   de enero de 1970 hasta el momento definido en el objeto date
            // setTime(); Establece una fecha pasandole en milisegundos el valor de esta.
            // getYear(); devuelve el año
            // getFullYear(); devuelve el año
            dteDate=new Date(year,month,day);

            //Devuelva true o false...
            return ((day==dteDate.getDate()) && (month==dteDate.getMonth()) && (year==dteDate.getFullYear()));
        }

        /**
         * Funcion para validar una fecha
         * Tiene que recibir:
         *  La fecha en formato ingles yyyy-mm-dd
         * Devuelve:
         *  true-Fecha correcta
         *  false-Fecha Incorrecta
         */
        function validate_fecha(fecha)
        {
            var patron=new RegExp("^(19|20)+([0-9]{2})([-])([0-9]{1,2})([-])([0-9]{1,2})$");

            if(fecha.search(patron)==0)
            {
                var values=fecha.split("-");
                if(isValidDate(values[2],values[1],values[0]))
                {
                    return true;
                }
            }
            return false;
        }

        /**
         * Esta función calcula la edad de una persona y los meses
         * La fecha la tiene que tener el formato yyyy-mm-dd que es
         * metodo que por defecto lo devuelve el <input type="date">
         */
        function calcularEdad()
        {
            var fecha=document.getElementById("fecha_nac").value;
            if(validate_fecha(fecha)==true)
            {
                // Si la fecha es correcta, calculamos la edad
                var values=fecha.split("-");
                var dia = values[2];
                var mes = values[1];
                var ano = values[0];

                // cogemos los valores actuales
                var fecha_hoy = new Date();
                var ahora_ano = fecha_hoy.getYear();
                var ahora_mes = fecha_hoy.getMonth()+1;
                var ahora_dia = fecha_hoy.getDate();

                // realizamos el calculo
                var edad = (ahora_ano + 1900) - ano;
                if ( ahora_mes < mes )
                {
                    edad--;
                }
                if ((mes == ahora_mes) && (ahora_dia < dia))
                {
                    edad--;
                }
                if (edad > 1900)
                {
                    edad -= 1900;
                }

                // calculamos los meses
                var meses=0;
                if(ahora_mes>mes)
                    meses=ahora_mes-mes;
                if(ahora_mes<mes)
                    meses=12-(mes-ahora_mes);
                if(ahora_mes==mes && dia>ahora_dia)
                    meses=11;

                // calculamos los dias
                var dias=0;
                if(ahora_dia>dia)
                    dias=ahora_dia-dia;
                if(ahora_dia<dia)
                {
                    ultimoDiaMes=new Date(ahora_ano, ahora_mes, 0);
                    dias=ultimoDiaMes.getDate()-(dia-ahora_dia);
                }

                return edad ;
            }else{
                document.getElementById("result").innerHTML="La fecha "+fecha+" es incorrecta";
            }
        }
    </script>



<?php
}
?>