<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AplicacionController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\CircuitoController;
use App\Http\Controllers\SubcircuitoController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RangoController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\SugerenciaController;
use App\Http\Controllers\DependenciaController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\EntidadController;
use App\Http\Controllers\SolicitudVehiculoController;
use App\Http\Controllers\OrdenMantenimientoController;
use App\Http\Controllers\OrdenAbastecimientoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/perfiles',[PerfilController::class,'index']);
Route::get('/perfiles/{all}',[PerfilController::class,'read']);
Route::get('/perfiles/{id}',[PerfilController::class,'read']);
Route::post('/perfiles',[PerfilController::class,'create']);
Route::put('/perfiles',[PerfilController::class,'update']);

Route::get('/usuarios',[UsuarioController::class,'index']);
Route::get('/usuarios/{all}',[UsuarioController::class,'read']);
Route::get('/usuarios/{id}',[UsuarioController::class,'read']);
Route::post('/usuarios',[UsuarioController::class,'create']);
Route::put('/usuarios',[UsuarioController::class,'update']);

Route::get('/login/{us}/{pw}',[UsuarioController::class,'login']);
Route::get('/aplicaciones/perfil/{pf}',[AplicacionController::class,'profile']);
Route::get('/aplicaciones/permisos-perfil/{pf}',[AplicacionController::class,'permissions']);
Route::patch('/aplicaciones/permisos-perfil',[AplicacionController::class,'savePermissions']);

Route::get('/distritostest',[DistritoController::class,'test']);
Route::get('/distritos',[DistritoController::class,'index']);
Route::get('/distritos/{all}',[DistritoController::class,'read']);
Route::get('/distritos/{id}',[DistritoController::class,'read']);
Route::post('/distritos',[DistritoController::class,'create']);
Route::put('/distritos',[DistritoController::class,'update']);

Route::get('/circuitos',[CircuitoController::class,'index']);
Route::get('/circuitos/distrito/{id}',[CircuitoController::class,'index']);
Route::get('/circuitos/{all}',[CircuitoController::class,'read']);
Route::get('/circuitos/{id}',[CircuitoController::class,'read']);
Route::post('/circuitos',[CircuitoController::class,'create']);
Route::put('/circuitos',[CircuitoController::class,'update']);

Route::get('/subcircuitos',[SubcircuitoController::class,'index']);
Route::get('/subcircuitos/circuito/{id}',[SubcircuitoController::class,'index']);
Route::get('/subcircuitos/{all}',[SubcircuitoController::class,'read']);
Route::get('/subcircuitos/{id}',[SubcircuitoController::class,'read']);
Route::post('/subcircuitos',[SubcircuitoController::class,'create']);
Route::put('/subcircuitos',[SubcircuitoController::class,'update']);

Route::get('/paises',[PaisController::class,'index']);
Route::get('/paises/{all}',[PaisController::class,'read']);
Route::get('/paises/{id}',[PaisController::class,'read']);

Route::get('/provincias',[ProvinciaController::class,'index']);
Route::get('/provincias/pais/{id}',[ProvinciaController::class,'index']);
Route::get('/provincias/{all}',[ProvinciaController::class,'read']);
Route::get('/provincias/{id}',[ProvinciaController::class,'read']);

Route::get('/ciudades',[CiudadController::class,'index']);
Route::get('/ciudades/provincia/{id}',[CiudadController::class,'index']);
Route::get('/ciudades/{all}',[CiudadController::class,'read']);
Route::get('/ciudades/{id}',[CiudadController::class,'read']);

Route::get('/personas',[PersonaController::class,'index']);
Route::get('/personas/{all}',[PersonaController::class,'read']);
Route::get('/personas/{id}',[PersonaController::class,'read']);
Route::post('/personas',[PersonaController::class,'create']);
Route::put('/personas',[PersonaController::class,'update']);

Route::get('/dependencias/personas/{id}',[DependenciaController::class,'getFromPerson']);
Route::patch('/dependencias/personas',[DependenciaController::class,'saveFromPerson']);
Route::get('/dependencias/vehiculos/{id}',[DependenciaController::class,'getFromVehicle']);
Route::patch('/dependencias/vehiculos',[DependenciaController::class,'saveFromVehiculo']);

Route::get('/custodios/persona/{id}',[DependenciaController::class,'getFromPerson']);
Route::post('/custodios/dependencias',[DependenciaController::class,'getCustodiansFromDependencies']);
Route::patch('/custodios/dependencias',[DependenciaController::class,'saveCustodiansFromDependencies']);
Route::get('/custodios/vehiculo/{id}',[DependenciaController::class,'getCustodiansFromVehicle']);
Route::get('/usuarios/entidad/{id}',[DependenciaController::class,'getUsersFromEntity']);
Route::patch('/usuarios/entidad',[DependenciaController::class,'saveUsersFromEntity']);

Route::get('/rangos',[RangoController::class,'index']);
Route::get('/rangos/{all}',[RangoController::class,'read']);
Route::get('/rangos/{id}',[RangoController::class,'read']);
Route::post('/rangos',[RangoController::class,'create']);
Route::put('/rangos',[RangoController::class,'update']);

Route::get('/contactos/tipos',[ContactoController::class,'tipos']);
Route::get('/contactos/tipos/{id}',[ContactoController::class,'tipos']);
Route::get('/contactos/personas',[ContactoController::class,'personas']);
Route::get('/contactos/personas/{id}',[ContactoController::class,'personas']);
Route::patch('/contactos',[ContactoController::class,'save']);

Route::get('/vehiculos',[VehiculoController::class,'index']);
Route::get('/vehiculos/{all}',[VehiculoController::class,'read']);
Route::get('/vehiculos/{id}',[VehiculoController::class,'read']);
Route::post('/vehiculos',[VehiculoController::class,'create']);
Route::put('/vehiculos',[VehiculoController::class,'update']);

Route::get('/vehiculo/tipos',[VehiculoController::class,'tipos']);
Route::get('/vehiculo/tipos/{id}',[VehiculoController::class,'tipos']);
Route::get('/vehiculo/marcas',[VehiculoController::class,'marcas']);
Route::get('/vehiculo/marcas/{id}',[VehiculoController::class,'marcas']);
Route::get('/vehiculo/modelos',[VehiculoController::class,'modelos']);
Route::get('/vehiculo/modelos/{id}',[VehiculoController::class,'modelos']);
Route::get('/vehiculo/personas/{id}',[VehiculoController::class,'getVehiclesFromPerson']);
Route::get('/vehiculo/historial/{type}/{id}',[VehiculoController::class,'getVehicleHistory']);

Route::get('/contrato/tipos',[ContratoController::class,'tipos']);
Route::get('/contrato/tipos/{id}',[ContratoController::class,'tipos']);
Route::get('/contratos/por-tipo/{id}',[ContratoController::class,'porTipos']);
Route::get('/contratos',[ContratoController::class,'index']);
Route::get('/contratos/{id}',[ContratoController::class,'read']);
Route::post('/contratos',[ContratoController::class,'create']);
Route::put('/contratos',[ContratoController::class,'update']);

Route::get('/entidades',[EntidadController::class,'index']);
Route::get('/entidades/{all}',[EntidadController::class,'read']);
Route::get('/entidades/{id}',[EntidadController::class,'read']);
Route::post('/entidades',[EntidadController::class,'create']);
Route::put('/entidades',[EntidadController::class,'update']);
Route::get('/entidades/por-tipo/{id}',[EntidadController::class,'getEntitiesByType']);
Route::get('/entidades/contrato/{id}',[EntidadController::class,'getEntitiesFromContract']);
Route::get('/entidades/contrato/{id}/{type}',[EntidadController::class,'getEntitiesFromContract']);
Route::patch('/entidades/contrato',[EntidadController::class,'saveEntitiesFromContract']);

Route::get('/solicitud-vehiculos',[SolicitudVehiculoController::class,'index']);
Route::get('/solicitud-vehiculos/{id}',[SolicitudVehiculoController::class,'read']);
Route::post('/solicitud-vehiculos',[SolicitudVehiculoController::class,'create']);
Route::put('/solicitud-vehiculos',[SolicitudVehiculoController::class,'update']);
Route::get('/solicitud-vehiculos/persona/{id}',[SolicitudVehiculoController::class,'getVehicleRequestsByPerson']);
Route::get('/solicitud-vehiculos-aprobadas/{type}/persona/{person}',[SolicitudVehiculoController::class,'getApprovedVehicleRequestsByPerson']);

Route::get('/orden-mantenimiento/{id}/persona/{person}',[OrdenMantenimientoController::class,'getOrderByPerson']);
Route::put('/orden-mantenimiento',[OrdenMantenimientoController::class,'update']);
Route::get('/orden-mantenimiento/{id}/actividades',[OrdenMantenimientoActividadController::class,'getActivitiesFromOrder']);

Route::get('/orden-abastecimientos/{id}/persona/{person}',[OrdenAbastecimientoController::class,'getOrderByPerson']);
Route::get('/orden-abastecimientos/reporte/{ini}/{end}',[OrdenAbastecimientoController::class,'getReport']);
Route::put('/orden-abastecimientos',[OrdenAbastecimientoController::class,'update']);

Route::get('/sugerencias',[SugerenciaController::class,'index']);
Route::get('/sugerencias/{all}',[SugerenciaController::class,'read']);
Route::get('/sugerencias/{id}',[SugerenciaController::class,'read']);
Route::post('/sugerencias',[SugerenciaController::class,'create']);
Route::put('/sugerencias',[SugerenciaController::class,'update']);

