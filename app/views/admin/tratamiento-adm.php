<script src="<?php echo asset_url("js/vendor/jquery-1.11.1.min.js") ?>"></script>
<?php

if (isset($_GET['id'])){
    $tratamiento = TratamientosPacientes::find($_GET['id']);
}else{
    $tratamiento = new TratamientosPacientes;
}

$guardado=0;
if (isset($_POST['submit']) && csrf_filter()) {

    $tratamiento->nombre = $_POST['tratamiento'];
    $tratamiento->idpaciente = $_POST['paciente_id'];
    $tratamiento->tipo = "Tratamiento";
	$tratamiento->fecha = date('Y-m-d', strtotime($_POST['fecha']));


    $data = array(


        'nomre' => $_POST['tratamiento'],
        'paciente_id' => $_POST['paciente_id'],
        'tipo' => "Tratamiento",
        'fecha' => date('Y-m-d', strtotime($_POST['fecha'])),

    );
    $rules = array(
        'fecha' => 'required',
        'paciente_id' => 'required',
    );

  	$validator = Validator::make($data, $rules);
	
	if ($validator->passes()) {

		if ($ver = $tratamiento->save()) {

		
			$guardado=1;
			
			redirect_to($_SERVER['REQUEST_URI'], array('tratamiento_added' => true));
		} else {
			$errors = new Hazzard\Support\MessageBag(array('error' => trans('errors.dbsave')));
		}
	} else {
		$errors = $validator->errors();
	}
}

?>
<?php
if (isset($_GET['pac_id'])){
    $paciente = Pacientes::find($_GET['pac_id'], $columns = array('id','nombre', 'apellido'));
}else{
    $paciente = Pacientes::find($presupuesto->paciente_id, $columns = array('id','nombre', 'apellido'));
}


?>
<h3 class="page-header">Tratamiento para <?php echo $paciente->nombre."".$paciente->apellido ?> </h3>
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
            <!-- PACIENTE -->
            <input type="hidden" name="paciente_id" id="paciente_id" value="<?php echo $paciente->id ?>" class="form-control">
            <br>
            <!-- FECHA  -->
            <div class="col-md-4">
                <label for="fecha">Fecha </label>
                <div class='input-group date' id='datetimepickerDesde'>
                    <input type="text" name="fecha" id="fecha" value="<?php echo $tratamiento->fecha ?>" class="form-control">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </div>
            </div>

            <!-- TRATAMIENTOs aqui debo poner un combo de los tratamientos de los clientes -->
            <div class="col-md-10">
                <div class="form-group">
                    <label for="tratamiento">Tratamientos <em><?php _e('admin.required') ?></em></label>
                    <select name="tratamiento" id="tratamiento" class="form-control">
                    <?php foreach ((array) Tratamientos::all() as $tratamiento){
                        echo '<option value="'.$tratamiento->tratamiento.'" '. ($tratamiento->tratamiento == $tratamiento->tratamiento ? 'selected' : '').'>'.$tratamiento->tratamiento.'</option>';
                    }?>
                    </select>
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

<?php echo View::make('admin.footer')->render();
$res = Variable::find(1, array('valor'));
?>


<script>

	$(function() {


		var Dolarval = <?php echo $res['valor']; ?> ;

        $("#hon_med_pesos").keyup(function(){
            var value = $(this).val()/Dolarval;
           // $("#hon_med_dolar").val(value.toFixed(2));

            $("#sub_pesos").val(parseFloat($(this).val()));
         //   $("#sub_dolar").val(parseFloat($(this).val()/Dolarval).toFixed(2));

            var value2 = parseFloat($("#sub_pesos").val()) - parseFloat($("#desc_pesos").val());
            $("#tot_pesos").val(value2.toFixed(2));
          //  $("#tot_dolar").val((value2/Dolarval).toFixed(2));
        });
        $("#Ane_pesos").keyup(function(){
            var value = $(this).val()/Dolarval;
         //   $("#Ane_dolar").val(value.toFixed(2));

            var sub = parseFloat($(this).val())+ parseFloat($("#hon_med_pesos").val());
            $("#sub_pesos").val(parseFloat(sub));
          //  $("#sub_dolar").val(parseFloat($("#sub_pesos").val()/Dolarval).toFixed(2));

            var value2 = parseFloat(sub) - parseFloat($("#desc_pesos").val());
            $("#tot_pesos").val(value2.toFixed(2));
        //    $("#tot_dolar").val((value2/Dolarval).toFixed(2));
        });
        $("#gas_san_pesos").keyup(function(){
            var value = $(this).val()/Dolarval;
          //  $("#gas_san_dolar").val(value.toFixed(2));
            var sub = parseFloat($(this).val())+ parseFloat($("#hon_med_pesos").val())+ parseFloat($("#Ane_pesos").val());
            $("#sub_pesos").val(parseFloat(sub));
          //  $("#sub_dolar").val(parseFloat($("#sub_pesos").val()/Dolarval).toFixed(2));

            var value2 = parseFloat(sub) - parseFloat($("#desc_pesos").val());
            $("#tot_pesos").val(value2.toFixed(2));
          //  $("#tot_dolar").val((value2/Dolarval).toFixed(2));
        });
        $("#int_pesos").keyup(function(){
            var value = $(this).val()/Dolarval;
        //    $("#int_dolar").val(value.toFixed(2));

            var sub = parseFloat($(this).val())+
                parseFloat($("#hon_med_pesos").val())+
                parseFloat($("#Ane_pesos").val()) +
                parseFloat($("#gas_san_pesos").val());

            $("#sub_pesos").val(parseFloat(sub));
          //  $("#sub_dolar").val(parseFloat($("#sub_pesos").val()/Dolarval).toFixed(2));

            var value2 = parseFloat(sub) - parseFloat($("#desc_pesos").val());
            $("#tot_pesos").val(value2.toFixed(2));
         //   $("#tot_dolar").val((value2/Dolarval).toFixed(2));

        });
        $("#pro_pesos").keyup(function(){
            var value = $(this).val()/Dolarval;
        //    $("#pro_dolar").val(value.toFixed(2));

            var sub = parseFloat($(this).val())+
                parseFloat($("#hon_med_pesos").val())+
                parseFloat($("#Ane_pesos").val()) +
                parseFloat($("#gas_san_pesos").val())+
                parseFloat($("#int_pesos").val());
            $("#sub_pesos").val(parseFloat(sub));
          //  $("#sub_dolar").val(parseFloat($("#sub_pesos").val()/Dolarval).toFixed(2));

            var value2 = parseFloat(sub) - parseFloat($("#desc_pesos").val());
            $("#tot_pesos").val(value2.toFixed(2));
          //  $("#tot_dolar").val((value2/Dolarval).toFixed(2));

        });
        $("#otr_gas_pesos").keyup(function(){
            var value = $(this).val()/Dolarval;
         //   $("#otr_gas_dolar").val(value.toFixed(2));
            var sub = parseFloat($(this).val())+
                parseFloat($("#hon_med_pesos").val())+
                parseFloat($("#Ane_pesos").val()) +
                parseFloat($("#gas_san_pesos").val() )+
                parseFloat($("#int_pesos").val() )+
                parseFloat($("#pro_pesos").val());
            $("#sub_pesos").val(parseFloat(sub));
        //    $("#sub_dolar").val(parseFloat($("#sub_pesos").val()/Dolarval).toFixed(2));

            var value2 = parseFloat(sub) - parseFloat($("#desc_pesos").val());
            $("#tot_pesos").val(value2.toFixed(2));
         //   $("#tot_dolar").val((value2/Dolarval).toFixed(2));
        });

        $("#desc_pesos").keyup(function(){
            var value = $(this).val()/Dolarval;
        //    $("#desc_dolar").val(value.toFixed(2));

            var value2 = parseFloat($("#sub_pesos").val()) - parseFloat($(this).val());
            $("#tot_pesos").val(value2.toFixed(2));
         //   $("#tot_dolar").val((value2/Dolarval).toFixed(2));

        });

       $("#tot_pesos").change(function(){
            var value = $(this).val()/Dolarval;
          //  $("#tot_dolar").val(value.toFixed(2));
        });






    });





</script>
