<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('orden_movilizaciones');

        Schema::create('orden_movilizaciones', function (Blueprint $table) {
            $table->id('od_codigo');

            $table->unsignedBigInteger('sv_codigo')->nullable(); // ID de la Solicitud de la persona

            $table->string('od_ocupantes')->nullable(); // Lista de ocupantes
         
            $table->boolean('od_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();$table->foreign('created_by')->references('us_codigo')->on('usuarios')->onDelete('set null');
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();$table->foreign('updated_by')->references('us_codigo')->on('usuarios')->onDelete('set null');

            $table->foreign('sv_codigo')->references('sv_codigo')->on('solicitud_vehiculos')->onDelete('set null');
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
        Schema::dropIfExists('orden_movilizaciones');
        /************************************/
        Schema::enableForeignKeyConstraints();
    }
};
