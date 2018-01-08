<?php
require_once 'app/init.php';

//$query = Presupuesto::join($PacienteTable, "{$PresupuestoTable}.paciente_id", '=', "{$PacienteTable}.id", 'inner');

$res = Presupuesto::join('pacientes', "presupuestos.paciente_id", '=', "pacientes.id", 'inner')
    ->select( 'pacientes.id',  DB::raw('CONCAT(pacientes.nombre," ",pacientes.apellido) as full_name'))
    ->get();

print_r($res);