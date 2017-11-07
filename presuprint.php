<?php
require_once 'app/init.php';


$res = Presupuesto::join('pacientes', "presupuestos.paciente_id", '=', "pacientes.id", 'inner')
    ->where('presupuestos.id', '=', $_GET['id'])
    ->select( '*',  DB::raw('CONCAT(pacientes.nombre," ",pacientes.apellido) as full_name'))
    ->first();

//print_r($res->full_name);
?>
<div class="row" align="center">
    <h3>Presupuesto <?php echo $res->full_name ?> </h3>

    <hr>
   <table>
       <tr>
           <TD>FECHA: <?php echo $res->fecha ?> </TD>
           <TD>ESTADO: <?php echo $res->estado ?> </TD>
           <TD>VALIDEZ: <?php echo $res->validez ?></TD>
       </tr>
       <tr>
        <?php $tratamiento = Tratamientos::where('id','=',$res->tratamiento)->select('tratamiento')->get();?>
           <TD><h4>TRATAMIENTO: <?php echo $tratamiento[0]->tratamiento ?></h4> </TD>
       </tr>
       <tr><td></td><td align="right">Pesos</td><td align="right">Dolar</td></tr>
           <TR>
           <TD>Honorarios Médicos: <?php echo $res->honorarios_medicos ?> </TD>
           <TD align="right"> <?php echo "$ ". number_format($res->hon_med_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->hon_med_dolar, 2, ',', '.') ?> </TD>
       </TR>
       <TR>
           <TD>Anestesia: </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->Ane_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->Ane_dolar, 2, ',', '.') ?> </TD>
       </TR>
       <TR>
           <TD>Gastos sanatoriales: </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->gas_san_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->gas_san_dolar, 2, ',', '.') ?> </TD>
       </TR>
       <TR>
           <TD>Internación: </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->int_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->int_dolar, 2, ',', '.') ?> </TD>
       </TR>
       <TR>
           <TD>Protesis: </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->pro_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->pro_dolar, 2, ',', '.') ?> </TD>
       </TR>
       <TR>
           <TD>Otros Gastos: <?php echo $res->otros_gastos ?></TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->otr_gas_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->otr_gas_dolar, 2, ',', '.') ?> </TD>
       </TR>

       <TR>
           <TD>DESCUENTO: </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->desc_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->desc_dolar, 2, ',', '.') ?> </TD>
       </TR>
       <TR>
           <TD>TOTAL: </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->tot_pesos, 2, ',', '.') ?> </TD>
           <TD align="right"> <?php echo  "$ ". number_format($res->tot_dolar, 2, ',', '.') ?> </TD>
       </TR>
       <TR>
           <TD>Observaciones: </TD>
           <TD> <?php echo $res->Ovservaciones ?> </TD>
       </TR>
   </table>