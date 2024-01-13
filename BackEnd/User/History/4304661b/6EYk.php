<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use DB;
use Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::dropIfExists('repuestos');
        
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id('re_codigo');
            $table->string('re_nombre')->unique();

            $table->boolean('re_estado')->default(true);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Filtro de aceite']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Filtro de combustible']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Filtro de polen']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Filtro habitáculo']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Batería']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Bombillas']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Neumáticos']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Pastillas de freno']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Discos de freno']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Amortiguadores']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Espejos retrovisores']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Llanta de repuesto']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Cinturón de seguridad']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Tapones de motor']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Tapones de transmisión']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Correas de distribución']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Rodamientos']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Cojinetes']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Tubo de escape']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Mangueras']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Líquidos de frenos']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Líquidos de aceite']);
        DB::table('repuestos')->insertOrIgnore(['re_nombre' => 'Líquidos de refrigerante']);
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
        Schema::dropIfExists('repuestos');
        /************************************/
        Schema::enableForeignKeyConstraints();
    }
};
