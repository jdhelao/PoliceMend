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
        
        Schema::dropIfExists('repueorden_mantenimiento_repuestosstos');
        
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
        Schema::dropIfExists('orden_mantenimiento_repuestos');
        /************************************/
        Schema::enableForeignKeyConstraints();
    }
};
