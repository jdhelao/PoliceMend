<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('perfiles');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('aplicaciones');
        Schema::dropIfExists('aplicacion_perfil');
        Schema::dropIfExists('catalogos');

        Schema::dropIfExists('paises');
        Schema::dropIfExists('provincias');
        Schema::dropIfExists('ciudades');
        Schema::dropIfExists('parroquias');

        Schema::dropIfExists('subcircuitos');
        Schema::dropIfExists('circuitos');
        Schema::dropIfExists('distritos');

        Schema::dropIfExists('rangos');
        Schema::dropIfExists('personas');

        Schema::dropIfExists('tipo_contactos');
        Schema::dropIfExists('contactos');
        Schema::dropIfExists('persona_subcircuitos');

        Schema::dropIfExists('vehiculo_tipos');
        Schema::dropIfExists('vehiculo_marcas');
        Schema::dropIfExists('vehiculo_modelos');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('vehiculo_subcircuitos');
        Schema::dropIfExists('vehiculo_custodios');
        Schema::dropIfExists('vehiculo_historial');

        Schema::dropIfExists('contrato_tipos');
        Schema::dropIfExists('contratos');
        Schema::dropIfExists('contrato_entidades');

        Schema::dropIfExists('entidades');
        Schema::dropIfExists('entidad_usuarios');

        Schema::dropIfExists('solicitud_vehiculos');
        Schema::dropIfExists('orden_mantenimientos');
        Schema::dropIfExists('orden_mantenimiento_actividades');
        Schema::dropIfExists('orden_mantenimiento_repuestos');
        Schema::dropIfExists('orden_abastecimientos');

        Schema::dropIfExists('repuestos');
        Schema::dropIfExists('sugerencias');

        Schema::create('perfiles', function (Blueprint $table) {
            $table->id('pf_codigo');
            $table->string('pf_nombre')->unique();

            $table->boolean('pf_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('us_codigo');
            $table->string('us_login')->unique();
            $table->unsignedBigInteger('pe_codigo')->nullable();
            $table->unsignedBigInteger('pf_codigo')->nullable();
            $table->string('us_password');

            $table->boolean('us_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('pf_codigo')->references('pf_codigo')->on('perfiles')->onDelete('set null');
            /*$table->foreign('pe_codigo')->references('pe_codigo')->on('personas')->onDelete('set null');*/
        });

        Schema::create('aplicaciones', function (Blueprint $table) {
            $table->id('ap_codigo');
            $table->string('ap_nombre');
            $table->string('ap_ruta');
            $table->string('ap_imagen')->nullable();

            $table->boolean('ap_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });
        Schema::create('aplicacion_perfil', function (Blueprint $table) {
            $table->unsignedBigInteger('pf_codigo')->nullable();
            $table->unsignedBigInteger('ap_codigo')->nullable();

            $table->boolean('ap_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('pf_codigo')->references('pf_codigo')->on('perfiles')->onDelete('set null');
            $table->foreign('ap_codigo')->references('ap_codigo')->on('aplicaciones')->onDelete('set null');

            $table->unique(['pf_codigo', 'ap_codigo']);
        });

        Schema::create('catalogos', function (Blueprint $table) {
            $table->id('ca_codigo');

            $table->string('ca_nombre')->nullable();
            $table->string('ca_prefijo')->nullable();
            $table->string('ca_tabla')->nullable();

            $table->boolean('ca_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });

        /*División política*/
        Schema::create('paises', function (Blueprint $table) {
            $table->id('pa_codigo');
            $table->string('pa_nombre');

            $table->boolean('pa_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });
        Schema::create('provincias', function (Blueprint $table) {
            $table->id('pr_codigo');
            $table->unsignedBigInteger('pa_codigo')->nullable();
            $table->string('pr_nombre');

            $table->boolean('pr_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('pa_codigo')->references('pa_codigo')->on('paises')->onDelete('set null');
        });
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id('ci_codigo');
            $table->unsignedBigInteger('pr_codigo')->nullable();
            $table->string('ci_nombre');

            $table->boolean('ci_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('pr_codigo')->references('pr_codigo')->on('provincias')->onDelete('set null');
        });
        Schema::create('parroquias', function (Blueprint $table) {
            $table->id('cp_codigo');
            $table->unsignedBigInteger('ci_codigo')->nullable();
            $table->string('cp_nombre');

            $table->boolean('cp_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('ci_codigo')->references('ci_codigo')->on('ciudades')->onDelete('set null');
        });

        /*Dependencias*/
        Schema::create('distritos', function (Blueprint $table) {
            $table->string('di_codigo',5)->primary();
            $table->string('di_nombre');

            $table->boolean('di_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });
        Schema::create('circuitos', function (Blueprint $table) {
            $table->string('cc_codigo',8)->primary();
            $table->string('di_codigo',5)->nullable();
            $table->string('cc_nombre');

            $table->boolean('cc_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('di_codigo')->references('di_codigo')->on('distritos')->onDelete('set null');
        });
        Schema::create('subcircuitos', function (Blueprint $table) {
            $table->string('sc_codigo',11)->primary();
            $table->string('cc_codigo',8)->nullable();
            $table->string('sc_nombre');

            $table->boolean('sc_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('cc_codigo')->references('cc_codigo')->on('circuitos')->onDelete('set null');
        });

        /*Personal*/
        Schema::create('rangos', function (Blueprint $table) {
            $table->id('ra_codigo');
            $table->string('ra_nombre')->unique();
            $table->string('ra_abreviacion',10)->nullable();
            $table->smallinteger('ra_jerarquia')->nullable();

            $table->boolean('ra_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });

        Schema::create('personas', function (Blueprint $table) {
            $table->id('pe_codigo');
            $table->string('pe_dni', 10)->unique();
            $table->string('pe_nombre1');
            $table->string('pe_nombre2')->nullable();
            $table->string('pe_apellido1');
            $table->string('pe_apellido2')->nullable();
            $table->string('pe_sangre',3)->nullable();
            $table->datetime('pe_fnacimiento')->nullable();
            $table->unsignedBigInteger('ci_codigo')->nullable();
            $table->unsignedBigInteger('ra_codigo')->nullable();

            $table->boolean('pe_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('ci_codigo')->references('ci_codigo')->on('ciudades')->onDelete('set null');
            $table->foreign('ra_codigo')->references('ra_codigo')->on('rangos')->onDelete('set null');
        });

        /*Contactos*/
        Schema::create('tipo_contactos', function (Blueprint $table) {
            $table->id('tc_codigo');
            $table->string('tc_nombre')->unique();
            $table->smallinteger('tc_min')->nullable();
            $table->smallinteger('tc_max')->nullable();
            $table->string('tc_rex')->nullable();
            $table->string('tc_ico')->nullable();

            $table->boolean('tc_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });
        Schema::create('contactos', function (Blueprint $table) {
            $table->id('co_codigo');

            $table->unsignedBigInteger('pe_codigo')->nullable();
            $table->unsignedBigInteger('tc_codigo')->nullable();

            $table->string('co_descripcion')->nullable();

            $table->boolean('co_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('pe_codigo')->references('pe_codigo')->on('personas')->onDelete('set null');
            $table->foreign('tc_codigo')->references('tc_codigo')->on('tipo_contactos')->onDelete('set null');
        });
        Schema::create('persona_subcircuitos', function (Blueprint $table) {
            $table->id('ps_codigo');

            $table->unsignedBigInteger('pe_codigo')->nullable();
            $table->string('sc_codigo',11)->nullable();

            $table->boolean('ps_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('pe_codigo')->references('pe_codigo')->on('personas')->onDelete('set null');
            $table->foreign('sc_codigo')->references('sc_codigo')->on('subcircuitos')->onDelete('set null');
        });

        /*Vehículos*/
        Schema::create('vehiculo_tipos', function (Blueprint $table) {
            $table->id('vt_codigo');
            $table->string('vt_nombre');

            $table->boolean('vt_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });
        Schema::create('vehiculo_marcas', function (Blueprint $table) {
            $table->id('vm_codigo');
            $table->string('vm_nombre');

            $table->boolean('vm_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });
        Schema::create('vehiculo_modelos', function (Blueprint $table) {
            $table->id('mm_codigo');
            $table->unsignedBigInteger('vm_codigo')->nullable();
            $table->string('mm_nombre');

            $table->boolean('mm_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('vm_codigo')->references('vm_codigo')->on('vehiculo_marcas')->onDelete('set null');
        });

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id('ve_codigo');
            $table->string('ve_placa')->nullable()->unique();
            $table->string('ve_chasis')->nullable()->unique();
            $table->string('ve_motor')->nullable();

            $table->unsignedBigInteger('vt_codigo')->nullable();
            $table->unsignedBigInteger('vm_codigo')->nullable();
            $table->unsignedBigInteger('pa_codigo')->nullable();

            $table->string('ve_modelo')->nullable();
            $table->smallInteger('ve_anio')->nullable();

            $table->float('ve_cilindaraje')->nullable();/*$table->float('ve_tamanioMotor')->nullable(); /*2.0L, 3.5L, 5.7L*/
            $table->float('ve_capacidadCarga')->nullable();/*0.25 en motos*/
            $table->tinyInteger('ve_capacidadPasajero')->nullable();
            $table->integer('ve_km')->nullable();

            $table->string('ve_color')->nullable();
            $table->string('ve_color2')->nullable(); /*Sin valor*/

            $table->string('ve_combustible')->nullable(); /*Gasolina, diesel, electrico, hybrid*/
            $table->integer('ve_torque')->nullable(); /*200 lb-ft, 300 lb-ft, 400 lb-ft*/
            $table->string('ve_transmision')->nullable(); /*Automatico, manual, CVT*/
            $table->integer('ve_caballos')->nullable(); /*150, 200, 300, 400*/

            $table->boolean('ve_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('vt_codigo')->references('vt_codigo')->on('vehiculo_tipos')->onDelete('set null');
            $table->foreign('vm_codigo')->references('vm_codigo')->on('vehiculo_marcas')->onDelete('set null');
            $table->foreign('pa_codigo')->references('pa_codigo')->on('paises')->onDelete('set null');
        });

        Schema::create('vehiculo_subcircuitos', function (Blueprint $table) {
            $table->id('vs_codigo');

            $table->unsignedBigInteger('ve_codigo')->nullable();
            $table->string('sc_codigo',11)->nullable();

            $table->boolean('vs_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('ve_codigo')->references('ve_codigo')->on('vehiculos')->onDelete('set null');
            $table->foreign('sc_codigo')->references('sc_codigo')->on('subcircuitos')->onDelete('set null');
        });

        Schema::create('vehiculo_custodios', function (Blueprint $table) {
            $table->id('vc_codigo');

            $table->unsignedBigInteger('ve_codigo')->nullable();
            $table->unsignedBigInteger('pe_codigo')->nullable();

            $table->boolean('vc_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('ve_codigo')->references('ve_codigo')->on('vehiculos')->onDelete('set null');
            $table->foreign('pe_codigo')->references('pe_codigo')->on('personas')->onDelete('set null');
        });

        Schema::create('vehiculo_historiales', function (Blueprint $table) {
            $table->id('vh_codigo');

            $table->unsignedBigInteger('ve_codigo')->nullable();

            $table->string('vh_tipo',10)->nullable();
            $table->float('vh_valor')->nullable();

            $table->boolean('vh_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('ve_codigo')->references('ve_codigo')->on('vehiculos')->onDelete('set null');
        });

        /*Contratos*/
        Schema::create('contrato_tipos', function (Blueprint $table) {
            $table->id('kt_codigo');
            $table->string('kt_nombre');

            $table->boolean('kt_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });

        Schema::create('contratos', function (Blueprint $table) {
            $table->id('ko_codigo');
            $table->string('ko_documento')->nullable()->unique();
            $table->unsignedBigInteger('kt_codigo')->nullable();

            $table->datetime('ko_inicio')->nullable();
            $table->datetime('ko_fin')->nullable();

            $table->float('ko_monto')->nullable();

            $table->string('ko_compadecientes')->nullable(); /*Parties Involved*/

            $table->string('ko_clausulas')->nullable(); /*Terms and Conditions*/

            $table->unsignedBigInteger('kt_contratante')->nullable();
            $table->unsignedBigInteger('kt_contratista')->nullable();

            $table->boolean('ko_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('kt_codigo')->references('kt_codigo')->on('contrato_tipos')->onDelete('set null');
            $table->foreign('kt_contratante')->references('pe_codigo')->on('personas')->onDelete('set null');
            $table->foreign('kt_contratista')->references('pe_codigo')->on('personas')->onDelete('set null');
        });

        /*Instituciones o entidades externas*/
        Schema::create('entidades', function (Blueprint $table) {
            $table->id('en_codigo');

            $table->unsignedBigInteger('kt_codigo')->nullable(); /*tipo de relación(contrato,convenio,entidad) */
            $table->unsignedBigInteger('pe_codigo')->nullable(); /*representante*/
            $table->string('di_codigo',5)->nullable();

            $table->string('en_nombre')->nullable()/*->unique()*/;
            $table->string('en_franquicia')->nullable();
            $table->string('en_especialidad')->nullable();
            $table->boolean('en_24horas')->default(false)->nullable();

            $table->decimal('en_latitud',4)->nullable();
            $table->decimal('en_longitud',4)->nullable();
            /*
            $table->double('en_latitud', 15, 8);
            */
            $table->string('en_plus_code')->nullable();

            $table->boolean('en_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('kt_codigo')->references('kt_codigo')->on('contrato_tipos')->onDelete('set null');
            $table->foreign('pe_codigo')->references('pe_codigo')->on('personas')->onDelete('set null');
            $table->foreign('di_codigo')->references('di_codigo')->on('distritos')->onDelete('set null');
        });
        DB::statement('ALTER TABLE `entidades` MODIFY `en_latitud` FLOAT DEFAULT NULL');
        DB::statement('ALTER TABLE `entidades` MODIFY `en_longitud` FLOAT DEFAULT NULL');

        Schema::create('entidad_usuarios', function (Blueprint $table) {
            $table->id('eu_codigo');

            $table->unsignedBigInteger('en_codigo')->nullable();
            $table->unsignedBigInteger('us_codigo')->nullable();

            $table->boolean('eu_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('en_codigo')->references('en_codigo')->on('entidades')->onDelete('set null');
            $table->foreign('us_codigo')->references('us_codigo')->on('usuarios')->onDelete('set null');
        });

        Schema::create('contrato_entidades', function (Blueprint $table) {
            $table->id('ke_codigo');

            $table->unsignedBigInteger('ko_codigo')->nullable();
            $table->unsignedBigInteger('en_codigo')->nullable();

            $table->boolean('ke_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('ko_codigo')->references('ko_codigo')->on('contratos')->onDelete('set null');
            $table->foreign('en_codigo')->references('en_codigo')->on('entidades')->onDelete('set null');
        });

        /*Solicitudes de Mantenimiento/Abastecimiento*/
        Schema::create('solicitud_vehiculos', function (Blueprint $table) {
            $table->id('sv_codigo');

            $table->unsignedBigInteger('kt_codigo')->nullable(); /*tipo de relación(contrato,convenio,entidad) */
            $table->unsignedBigInteger('pe_codigo')->nullable(); /*Custodio*/
            $table->unsignedBigInteger('ve_codigo')->nullable(); /*Vehículo*/

            $table->integer('ve_km')->nullable();
            $table->tinyInteger('ve_combustible_nivel')->nullable();

            $table->datetime('sv_fecha_requerimiento')->nullable();
            $table->string('sv_descripcion')->nullable();

            $table->boolean('sv_aprobacion')->nullable();
            $table->string('sv_observacion')->nullable();
            $table->boolean('sv_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('ve_codigo')->references('ve_codigo')->on('vehiculos')->onDelete('set null');
            $table->foreign('pe_codigo')->references('pe_codigo')->on('personas')->onDelete('set null');
            $table->foreign('kt_codigo')->references('kt_codigo')->on('contrato_tipos')->onDelete('set null');
        });

        Schema::create('orden_mantenimientos', function (Blueprint $table) {
            $table->id('om_codigo');

            $table->unsignedBigInteger('sv_codigo')->nullable(); /*ID de la Solicitud de la persona*/
            $table->unsignedBigInteger('en_codigo')->nullable(); /*Entidad aprobada*/

            $table->float('om_total')->nullable();
            $table->unsignedBigInteger('pe_codigo')->nullable(); /*tecnico que atiende mantenimiento/reparación*/

            $table->boolean('om_ingreso_aceptacion')->nullable();
            $table->string('om_ingreso_condicion')->nullable();
            $table->boolean('om_entrega_aceptacion')->nullable();
            $table->string('om_entrega_condicion')->nullable();

            $table->tinyInteger('om_progreso')->nullable();

            $table->string('om_documento')->nullable(); /*Numero de factura*/
            $table->text('om_archivo_datos')->nullable(); /*guardar el archivo la factura/voucher in base64*/
            $table->string('om_archivo_tipo')->nullable(); /*guardar la extension del archivo*/

            $table->boolean('om_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('sv_codigo')->references('sv_codigo')->on('solicitud_vehiculos')->onDelete('set null');
            $table->foreign('pe_codigo')->references('pe_codigo')->on('personas')->onDelete('set null');
        });
        Schema::create('orden_mantenimiento_actividades', function (Blueprint $table) {
            $table->id('oma_codigo');

            $table->unsignedBigInteger('om_codigo')->nullable(); /*ID de la Orden de Mantenimiento*/
            $table->string('oma_descripcion')->nullable();
            $table->float('oma_costo')->nullable();

            $table->boolean('oma_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('om_codigo')->references('om_codigo')->on('orden_mantenimientos')->onDelete('set null');
        });
        Schema::create('orden_mantenimiento_repuestos', function (Blueprint $table) {
            $table->id('omr_codigo');

            $table->unsignedBigInteger('om_codigo')->nullable(); /*ID de la Orden de Mantenimiento*/
            $table->unsignedBigInteger('re_codigo')->nullable(); /*ID del repuesto*/

            $table->integer('omr_cantidad')->nullable();
            $table->float('omr_costo')->nullable();

            $table->boolean('omr_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('om_codigo')->references('om_codigo')->on('orden_mantenimientos')->onDelete('set null');
            $table->foreign('re_codigo')->references('re_codigo')->on('repuestos')->onDelete('set null');
        });

        Schema::create('orden_abastecimientos', function (Blueprint $table) {
            $table->id('oa_codigo');

            $table->unsignedBigInteger('sv_codigo')->nullable(); /*ID de la Solicitud de la persona*/
            $table->unsignedBigInteger('en_codigo')->nullable(); /*Entidad donde se realizó el consumo*/

            $table->float('oa_total')->nullable();
            $table->float('oa_galones')->nullable();
            $table->integer('oa_km')->nullable();
            $table->tinyInteger('oa_combustible_nivel')->nullable();

            $table->string('oa_documento')->nullable(); /*Numero de factura*/
            $table->text('oa_archivo_datos')->nullable(); /*guardar el archivo la factura/voucher in base64*/
            $table->string('oa_archivo_tipo')->nullable(); /*guardar la extension del archivo*/

            $table->boolean('oa_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('sv_codigo')->references('sv_codigo')->on('solicitud_vehiculos')->onDelete('set null');
        });

        Schema::create('repuestos', function (Blueprint $table) {
            $table->id('re_codigo');
            $table->string('re_nombre')->unique();

            $table->boolean('re_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        /*P4.1 - Caracteritica adicional*/
        Schema::create('sugerencias', function (Blueprint $table) {
            $table->id('su_codigo');
            $table->integer('su_tipo')->nullable();
            $table->string('sc_codigo',11)->nullable();
            $table->string('su_contacto');
            $table->string('su_nombres');
            $table->string('su_apellidos');
            $table->string('su_detalles');

            $table->boolean('su_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('sc_codigo')->references('sc_codigo')->on('subcircuitos')->onDelete('set null');
        });

        /************************************/
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        /************************************/
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('perfiles');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('aplicaciones');
        Schema::dropIfExists('aplicacion_perfil');
        Schema::dropIfExists('catalogos');

        Schema::dropIfExists('paises');
        Schema::dropIfExists('provincias');
        Schema::dropIfExists('ciudades');
        Schema::dropIfExists('parroquias');

        Schema::dropIfExists('subcircuitos');
        Schema::dropIfExists('circuitos');
        Schema::dropIfExists('distritos');

        Schema::dropIfExists('rangos');
        Schema::dropIfExists('personas');

        Schema::dropIfExists('tipo_contactos');
        Schema::dropIfExists('contactos');
        Schema::dropIfExists('persona_subcircuitos');

        Schema::dropIfExists('vehiculo_tipos');
        Schema::dropIfExists('vehiculo_marcas');
        Schema::dropIfExists('vehiculo_modelos');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('vehiculo_subcircuitos');
        Schema::dropIfExists('vehiculo_custodios');
        Schema::dropIfExists('vehiculo_historial');

        Schema::dropIfExists('contrato_tipos');
        Schema::dropIfExists('contratos');
        Schema::dropIfExists('contrato_entidades');

        Schema::dropIfExists('entidades');
        Schema::dropIfExists('entidad_usuarios');

        Schema::dropIfExists('solicitud_vehiculos');
        Schema::dropIfExists('orden_mantenimientos');
        Schema::dropIfExists('orden_mantenimiento_actividades');
        Schema::dropIfExists('orden_mantenimiento_repuestos');
        Schema::dropIfExists('orden_abastecimientos');

        Schema::dropIfExists('repuestos');
        Schema::dropIfExists('sugerencias');
        /************************************/
        Schema::enableForeignKeyConstraints();
    }
};
