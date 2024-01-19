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

        Schema::dropIfExists('catalogos');

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
        Schema::dropIfExists('catalogos');
        /************************************/
        Schema::enableForeignKeyConstraints();
    }
};
