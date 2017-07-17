<script src="<?php echo asset_url("js/vendor/jquery-1.11.1.min.js") ?>"></script>
<?php

if (isset($_GET['id'])){
    $presupuesto = Presupuesto::find($_GET['id']);
}else{
    $presupuesto = new Pacientes;
}

$guardado=0;
if (isset($_POST['submit']) && csrf_filter()) {

	$presupuesto->fecha = date('Y-m-d H:i:s', strtotime($_POST['fecha']));
	$presupuesto->paciente_id = $_POST['paciente_id'];
	$presupuesto->estado = $_POST['estado'];
	$presupuesto->tratamiento = $_POST['tratamiento'];
	$presupuesto->honorarios_medicos = $_POST['honorarios_medicos'];
	$presupuesto->hon_med_pesos = $_POST['hon_med_pesos'];
	$presupuesto->hon_med_dolar = $_POST['hon_med_dolar'];
	$presupuesto->Anestesia = $_POST['Anestesia'];
	$presupuesto->Ane_pesos = $_POST['Ane_pesos'];
	$presupuesto->Ane_dolar = $_POST['Ane_dolar'];
	$presupuesto->gastos_sanatoriales = $_POST['gastos_sanatoriales'];
	$presupuesto->gas_san_pesos = $_POST['gas_san_pesos'];
	$presupuesto->gas_san_dolar = $_POST['gas_san_dolar'];
	$presupuesto->internacion = $_POST['internacion'];
	$presupuesto->int_pesos = $_POST['int_pesos'];
	$presupuesto->int_dolar = $_POST['int_dolar'];
	$presupuesto->protesis = $_POST['protesis'];
	$presupuesto->pro_pesos = $_POST['pro_pesos'];
	$presupuesto->pro_dolar = $_POST['pro_dolar'];
	$presupuesto->otros_gastos = $_POST['otros_gastos'];
	$presupuesto->otr_gas_pesos = $_POST['otr_gas_pesos'];
	$presupuesto->otr_gas_dolar = $_POST['otr_gas_dolar'];
	$presupuesto->desc_pesos = $_POST['desc_pesos'];
	$presupuesto->desc_dolar = $_POST['desc_dolar'];
	$presupuesto->Observaciones = $_POST['Observaciones'];
	$presupuesto->forma_pago = "EFectivo";
	$presupuesto->validez = date('Y-m-d H:i:s', strtotime($_POST['validez']));



    $data = array(

        'fecha' => date('Y-m-d H:i:s', strtotime($_POST['fecha'])),
        'paciente_id' => $_POST['paciente_id'],
        'estado' => $_POST['estado'],
        'tratamiento' => $_POST['tratamiento'],
        'honorarios_medicos' => $_POST['honorarios_medicos'],
        'hon_med_pesos' => $_POST['hon_med_pesos'],
        'hon_med_dolar' => $_POST['hon_med_dolar'],
        'Anestesia' => $_POST['Anestesia'],
        'Ane_pesos' => $_POST['Ane_pesos'],
        'Ane_dolar' => $_POST['Ane_dolar'],
        'gastos_sanatoriales' => $_POST['gastos_sanatoriales'],
        'gas_san_pesos' => $_POST['gas_san_pesos'],
        'gas_san_dolar' => $_POST['gas_san_dolar'],
        'internacion' => $_POST['internacion'],
        'int_pesos' => $_POST['int_pesos'],
        'int_dolar' => $_POST['int_dolar'],
        'protesis' => $_POST['protesis'],
        'pro_pesos' => $_POST['pro_pesos'],
        'pro_dolar' => $_POST['pro_dolar'],
        'otros_gastos' => $_POST['otros_gastos'],
        'otr_gas_pesos' => $_POST['otr_gas_pesos'],
        'otr_gas_dolar' => $_POST['otr_gas_dolar'],
        'desc_pesos' => $_POST['desc_pesos'],
        'desc_dolar' => $_POST['desc_dolar'],
        'Observaciones' => $_POST['Observaciones'],
        'forma_pago' => "Efectivo",
        'validez' => date('Y-m-d H:i:s', strtotime($_POST['validez'])),
      //  'id_especialidad' => $_POST['id_especialidad'],
    );
    $rules = array(
        'fecha' => 'required',
        'paciente_id' => 'required',
        'tratamiento' => 'required',
        'forma_pago'=> 'required',
    );

  	$validator = Validator::make($data, $rules);
	
	if ($validator->passes()) {

		if ($ver = $presupuesto->save()) {

		
			$guardado=1;
			
			redirect_to('?p=pacientes', array('presupuesto_added' => true));
		} else {
			$errors = new Hazzard\Support\MessageBag(array('error' => trans('errors.dbsave')));
		}
	} else {
		$errors = $validator->errors();
	}
}

?>

<h3 class="page-header">Presupuesto </h3>
<p><a href="?page=fichas">Volver a fichas</a></p>

<div class="row">
	<div class="col-md-12">

		<?php if (isset($errors)) {
			echo '<div class="alert alert-danger"><span class="close" data-dismiss="alert">&times;</span><ul>';
			foreach ($errors->all('<li>:message</li>') as $error) {
			   echo $error;
			}
			echo '</ul></div>';
		} ?>

		<form action=""  method="POST" >
                <?php csrf_input() ?>

            <br>
            <!-- FECHA  -->
            <div class="col-md-4">
                <label for="fecha">Fecha </label>
                <div class='input-group date' id='datetimepickerDesde'>
                    <input type="text" name="fecha" id="fecha" value="<?php echo $presupuesto->fecha ?>" class="form-control">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </div>
            </div>
            <!-- ESTADO -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="Pendiente" tag="Pendiente" >Pendiente </option>
                        <option value="en curso" tag="en curso" >en curso </option>
                        <option value="cancelado" tag="cancelado" >cancelado </option>
                    </select>
                </div>
            </div>

            <!-- PACIENTE -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="paciente_id">Paciente</label>
                    <select name="paciente_id" id="paciente_id" class="form-control">
                        <?php foreach ((array) Pacientes::all() as $paciente) { ?>
                            <option <?php echo $paciente->id == $presupuesto->paciente_id ? 'selected' : '' ?>><?php echo $paciente->nombre." ".$paciente->apellido ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <!-- validez -->
            <div class="col-md-4">
                <label for="fecha">Validez </label>
                <div class='input-group date' id='datetimepickerDesde'>
                    <input type="text" name="validez" id="validez" value="<?php echo $presupuesto->validez ?>" class="form-control">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </div>
            </div>
            <!-- TRATAMIENTO -->
            <div class="col-md-10">
                <div class="form-group">
                    <label for="tratamiento">Tratamiento <em><?php _e('admin.required') ?></em></label>
                    <input type="text" name="tratamiento" id="tratamiento" value="<?php echo $presupuesto->tratamiento ?>" class="form-control">
                </div>
            </div>

            <div class="col-md-12">

            <!-- HONORARIOS MEDICOS -->
            <div class="col-md-5">
                <div class="form-group">
                    <label for="honorarios_medicos">HONORARIOS MEDICOS<em><?php _e('admin.required') ?></em></label>
                    <input type="text" name="honorarios_medicos" id="honorarios_medicos"
                     value="<?php echo $presupuesto->honorarios_medicos ?>" class="form-control">
                </div>
                </div>
                <!-- HONORARIOS MEDICOS PESOS -->
                <div class="col-md-2">
                <div class="form-group">
                    <label for="hon_med_pesos">PESOS</label>
                    <input type="text" name="hon_med_pesos" id="hon_med_pesos"
                       value="<?php echo $presupuesto->hon_med_pesos ?>" class="form-control">
                </div>
            </div>

            <!-- HONORARIOS MEDICOS DOLAR -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="hon_med_dolar">DOLAR</label>
                        <input type="text" name="hon_med_dolar" id="hon_med_dolar"
                            value="<?php echo $presupuesto->hon_med_dolar ?>" class="form-control">
                    </div>
                </div>
           </div>


               <!-- Anestesia -->
            <div class="col-md-12">

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="Anestesia">ANESTESIA<em><?php _e('admin.required') ?></em></label>
                        <input type="text" name="Anestesia" id="Anestesia"
                               value="<?php echo $presupuesto->Anestesia ?>" class="form-control">
                    </div>
                </div>
                <!-- Anestesia PESOS -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="Ane_pesos">PESOS</label>
                        <input type="text" name="Ane_pesos" id="Ane_pesos"
                               value="<?php echo $presupuesto->Ane_pesos ?>" class="form-control">
                    </div>
                </div>

                <!-- Anestesia dolar -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="Ane_dolar">DOLAR</label>
                        <input type="text" name="Ane_dolar" id="Ane_dolar"
                               value="<?php echo $presupuesto->Ane_dolar ?>" class="form-control">
                    </div>
                </div>
            </div>
               <!-- gastos_sanatoriales -->
            <div class="col-md-12">

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="gastos_sanatoriales">GASTOS SANATORIALES<em><?php _e('admin.required') ?></em></label>
                        <input type="text" name="gastos_sanatoriales" id="gastos_sanatoriales"
                               value="<?php echo $presupuesto->gastos_sanatoriales ?>" class="form-control">
                    </div>
                </div>
                <!-- gastos_sanatoriales PESOS -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="gas_san_pesos">PESOS</label>
                        <input type="text" name="gas_san_pesos" id="gas_san_pesos"
                               value="<?php echo $presupuesto->gas_san_pesos ?>" class="form-control">
                    </div>
                </div>

                <!-- gastos_sanatoriales dolar -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="gas_san_dolar">DOLAR</label>
                        <input type="text" name="gas_san_dolar" id="gas_san_dolar"
                               value="<?php echo $presupuesto->gas_san_dolar ?>" class="form-control">
                    </div>
                </div>
            </div>
                <!-- internacion -->
            <div class="col-md-12">

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="internacion">INTERNACION<em><?php _e('admin.required') ?></em></label>
                        <input type="text" name="internacion" id="internacion"
                               value="<?php echo $presupuesto->gastos_sanatoriales ?>" class="form-control">
                    </div>
                </div>
                <!-- internacion PESOS -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="int_pesos">PESOS</label>
                        <input type="text" name="int_pesos" id="int_pesos"
                               value="<?php echo $presupuesto->int_pesos ?>" class="form-control">
                    </div>
                </div>

                <!-- internacion dolar -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="int_dolar">DOLAR</label>
                        <input type="text" name="int_dolar" id="int_dolar"
                               value="<?php echo $presupuesto->int_dolar ?>" class="form-control">
                    </div>
                </div>
            </div>
            <!-- protesis -->
          <div class="col-md-12">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="protesis">PROTESIS<em><?php _e('admin.required') ?></em></label>
                    <input type="text" name="protesis" id="protesis"
                           value="<?php echo $presupuesto->protesis ?>" class="form-control">
                </div>
            </div>
            <!-- protesis PESOS -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="pro_pesos">PESOS</label>
                    <input type="text" name="pro_pesos" id="pro_pesos"
                           value="<?php echo $presupuesto->pro_pesos ?>" class="form-control">
                </div>
            </div>

            <!-- protesis dolar -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="pro_dolar">DOLAR</label>
                    <input type="text" name="pro_dolar" id="pro_dolar"
                           value="<?php echo $presupuesto->pro_dolar ?>" class="form-control">
                </div>
            </div>
        </div>
            <!-- otros_gastos -->
            <div class="col-md-12">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="otros_gastos">OTROS GASTOS<em><?php _e('admin.required') ?></em></label>
                        <input type="text" name="otros_gastos" id="otros_gastos"
                               value="<?php echo $presupuesto->otros_gastos ?>" class="form-control">
                    </div>
                </div>
                <!-- otros_gastos PESOS -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="otr_gas_pesos">PESOS</label>
                        <input type="text" name="otr_gas_pesos" id="otr_gas_pesos"
                               value="<?php echo $presupuesto->otr_gas_pesos ?>" class="form-control">
                    </div>
                </div>

                <!-- otros_gastos dolar -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="otr_gas_dolar">DOLAR</label>
                        <input type="text" name="otr_gas_dolar" id="otr_gas_dolar"
                               value="<?php echo $presupuesto->otr_gas_dolar ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-5" align="right"><LABEL>SUBTOTAL</LABEL></div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="desc_pesos">PESOS</label>
                        <input type="text" name="desc_pesos" id="desc_pesos"
                               value="<?php echo $presupuesto->desc_pesos ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="desc_dolar">DOLAR</label>
                        <input type="text" name="desc_dolar" id="desc_dolar"
                               value="<?php echo $presupuesto->desc_dolar ?>" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-5" ALIGN="right"><LABEL>DESCUENTO</LABEL></div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="desc_pesos">% PESOS</label>
                        <input type="text" name="desc_pesos" id="desc_pesos"
                               value="<?php echo $presupuesto->desc_pesos ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="desc_dolar">% DOLAR</label>
                        <input type="text" name="desc_dolar" id="desc_dolar"
                               value="<?php echo $presupuesto->desc_dolar ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-5" align="right"><LABEL>TOTAL</LABEL></div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="desc_pesos">PESOS</label>
                        <input type="text" name="desc_pesos" id="desc_pesos"
                               value="<?php echo $presupuesto->desc_pesos ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="desc_dolar">DOLAR</label>
                        <input type="text" name="desc_dolar" id="desc_dolar"
                               value="<?php echo $presupuesto->desc_dolar ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="Observaciones">OBSERVACIONES</label>
                    <input type="text" name="Observaciones" id="Observaciones" placeholder="Solo pago en efectivo"
                           value="<?php echo $presupuesto->Observaciones ?>" class="form-control">
                </div>
            </div>

            <div class="form-group col-md-12">
                            <br>
                            <button type="submit" name="submit" class="btn btn-primary">
                                GUARDAR
                            </button>
                        </div>


		</form>
		</div>
</div>

    <?php echo '
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
        
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    
            <script>
            $(function () {
            $("#fecha").datepicker({
                minDate: 0,
            });
            $("#validez").datepicker({
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
