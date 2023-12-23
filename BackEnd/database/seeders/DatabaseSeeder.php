<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);n
        DB::statement('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');

        DB::table('perfiles')->insertOrIgnore([
            'pf_codigo' => 0,
            'pf_nombre' => 'Ninguno',
            /*'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),*/
        ]);
        DB::table('perfiles')->insertOrIgnore(['pf_codigo' => 1,'pf_nombre' => 'Administrador']);
        DB::table('perfiles')->insertOrIgnore(['pf_codigo' => 2,'pf_nombre' => 'Custodio']);
        DB::table('perfiles')->insertOrIgnore(['pf_codigo' => 3,'pf_nombre' => 'Logística']);
        DB::table('perfiles')->insertOrIgnore(['pf_codigo' => 4,'pf_nombre' => 'Taller - Jefe']);
        DB::table('perfiles')->insertOrIgnore(['pf_codigo' => 5,'pf_nombre' => 'Taller - Técnico']);

        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999999','pe_nombre1' => 'Daniel','pe_apellido1' => 'Helao']);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999998','pe_nombre1' => 'Josué','pe_apellido1' => 'Véliz']);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999997','pe_nombre1' => 'AAA','pe_apellido1' => 'aaa','ra_codigo' => 2]);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999996','pe_nombre1' => 'BBB','pe_apellido1' => 'bbb','ra_codigo' => 12]);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999995','pe_nombre1' => 'CCC','pe_apellido1' => 'ccc','ra_codigo' => 11]);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999994','pe_nombre1' => 'DDD','pe_apellido1' => 'ddd']);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999993','pe_nombre1' => 'EEE','pe_apellido1' => 'eee']);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999992','pe_nombre1' => 'FFF','pe_apellido1' => 'fff']);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999991','pe_nombre1' => 'GGG','pe_apellido1' => 'ggg']);
        DB::table('personas')->insertOrIgnore(['pe_dni' => '0999999990','pe_nombre1' => 'HHH','pe_apellido1' => 'hhh']);

        DB::table('usuarios')->insertOrIgnore(['us_codigo' => 1,'us_login' => 'dhelao'          ,'pf_codigo' => 1,'pe_codigo' => 1,'us_password' => 'dd8e165fbae895ee31e2818a5cf830bb6dd42409f7e69c2c19212fbf680a8f0a']);
        DB::table('usuarios')->insertOrIgnore(['us_codigo' => 2,'us_login' => 'custodio'        ,'pf_codigo' => 2,'pe_codigo' => 2,'us_password' => '3b372d104f9c2949cf0b29181a14d8991bf51013c108144b008cd2464e504119']);
        DB::table('usuarios')->insertOrIgnore(['us_codigo' => 3,'us_login' => 'logistica'       ,'pf_codigo' => 3,'pe_codigo' => 3,'us_password' => Hash::make('123456')]);
        DB::table('usuarios')->insertOrIgnore([                 'us_login' => 'tallerjefe1'     ,'pf_codigo' => 4,'pe_codigo' => 4,'us_password' => Hash::make('123456')]);
        DB::table('usuarios')->insertOrIgnore([                 'us_login' => 'tallerjefe2'     ,'pf_codigo' => 4,'pe_codigo' => 5,'us_password' => Hash::make('123456')]);
        DB::table('usuarios')->insertOrIgnore([                 'us_login' => 'tallertecnico1'  ,'pf_codigo' => 5,'pe_codigo' => 6,'us_password' => Hash::make('123456')]);
        DB::table('usuarios')->insertOrIgnore([                 'us_login' => 'tallertecnico2'  ,'pf_codigo' => 5,'pe_codigo' => 7,'us_password' => Hash::make('123456')]);
        DB::table('usuarios')->insertOrIgnore([                 'us_login' => 'tallertecnico3'  ,'pf_codigo' => 5,'us_password' => Hash::make('123456')]);
        DB::table('usuarios')->insertOrIgnore([                 'us_login' => 'tallertecnico4'  ,'pf_codigo' => 5,'us_password' => Hash::make('123456')]);

        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 1, 'ap_nombre' => 'Usuarios' ,'ap_ruta' => '/admin/usuario', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 2, 'ap_nombre' => 'Distritos' ,'ap_ruta' => '/admin/distrito', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 3, 'ap_nombre' => 'Circuitos' ,'ap_ruta' => '/admin/circuito', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 4, 'ap_nombre' => 'Subcircuitos' ,'ap_ruta' => '/admin/subcircuito', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 5, 'ap_nombre' => 'Personal' ,'ap_ruta' => '/admin/personal', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 6, 'ap_nombre' => 'Vehículos' ,'ap_ruta' => '/admin/vehiculo', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 7, 'ap_nombre' => 'Contratos' ,'ap_ruta' => '/admin/contrato', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 8, 'ap_nombre' => 'Repuestos' ,'ap_ruta' => '/admin/respuesto', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 9, 'ap_nombre' => 'Catálogos' ,'ap_ruta' => '/admin/catalogo', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 10, 'ap_nombre' => 'Orden de Mantenimiento' ,'ap_ruta' => '/admin/orden-mantenimiento', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 11, 'ap_nombre' => 'Orden de Abastecimiento' ,'ap_ruta' => '/admin/orden-abastecimiento', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 12, 'ap_nombre' => 'Reporte sugerencias' ,'ap_ruta' => '/admin/reporte-sugerencias', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 13, 'ap_nombre' => 'Entidades' ,'ap_ruta' => '/admin/entidad', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 14, 'ap_nombre' => 'Solicitudes Vehiculares' ,'ap_ruta' => '/admin/solicitud-vehicular', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 15, 'ap_nombre' => 'Aprobar Solicitudes Vehiculares' ,'ap_ruta' => '/admin/aprobar-solicitud-vehicular', 'ap_imagen' => null]);
        DB::table('aplicaciones')->insertOrIgnore(['ap_codigo' => 16, 'ap_nombre' => 'Perfiles' ,'ap_ruta' => '/admin/perfil', 'ap_imagen' => null]);
        /*Admin*/
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 1]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 2]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 3]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 4]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 5]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 6]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 7]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 8]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 9]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 10]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 11]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 12]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 13]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 14]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 3, 'ap_codigo' => 15]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 3, 'ap_codigo' => 16]);
        /*Policia-Custodio*/
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 2, 'ap_codigo' => 10]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 2, 'ap_codigo' => 11]);
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 2, 'ap_codigo' => 14]);
        /*Jefe Logistica*/
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 3, 'ap_codigo' => 15]);
        /*Admin profiles and permissions admin*/
        DB::table('aplicacion_perfil')->insertOrIgnore(['pf_codigo' => 1, 'ap_codigo' => 16]);

        /*RANGOS, Fuente: https://ecuador.unir.net/actualidad-unir/rangos-policia-nacional-ecuador  ;   https://revistaseguridad360.com/noticias/comunicaciones/policia-rangos*/
        DB::table('rangos')->insertOrIgnore(['ra_codigo' => 0, 'ra_nombre' => 'Ninguno']);
        /*
        1.Servidoras o servidores policiales directivos
        Rol de Conducción y mando:*/
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'General Superior']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'General Inspector']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'General de Distrito']);
        /*Rol de Coordinación Operativa:*/
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Coronel']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Teniente Coronel']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Mayor']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Capitán']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Teniente']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Subteniente ']);
        /*
        2. Servidoras o servidores técnicos operativos
        Rol de Supervisión Operativa:*/
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Suboficial Mayor']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Suboficial Primero']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Suboficial Segundo']);
        /*Rol de Ejecución Operativa:*/
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Sargento Primero']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Sargento Segund']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Cabo Primero']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Cabo Segundo']);
        DB::table('rangos')->insertOrIgnore(['ra_nombre' => 'Policía']);

        /*Dependencias*/
        DB::table('distritos')->insertOrIgnore(['di_codigo' => '11D01', 'di_nombre' => 'Loja']);
        DB::table('distritos')->insertOrIgnore(['di_codigo' => '11D02', 'di_nombre' => 'Catamayo']);

        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C01', 'di_codigo' => '11D01', 'cc_nombre' => 'VILCABAMBA']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C02', 'di_codigo' => '11D01', 'cc_nombre' => 'YANGANA']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C03', 'di_codigo' => '11D01', 'cc_nombre' => 'MALACATOS']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C04', 'di_codigo' => '11D01', 'cc_nombre' => 'TAQUIL']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C05', 'di_codigo' => '11D01', 'cc_nombre' => 'ZAMORA HUAYCO']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C06', 'di_codigo' => '11D01', 'cc_nombre' => 'ESTEBAN GODOY']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C07', 'di_codigo' => '11D01', 'cc_nombre' => 'EL PARAISO']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C08', 'di_codigo' => '11D01', 'cc_nombre' => 'CELI ROMAN']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C09', 'di_codigo' => '11D01', 'cc_nombre' => 'IV CENTENARIO']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C10', 'di_codigo' => '11D01', 'cc_nombre' => 'TEBAIDA']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C11', 'di_codigo' => '11D01', 'cc_nombre' => 'LOS MOLINOS']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D01C12', 'di_codigo' => '11D01', 'cc_nombre' => 'CHONTACRUZ']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D02C01', 'di_codigo' => '11D02', 'cc_nombre' => 'EL TAMBO']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D02C02', 'di_codigo' => '11D02', 'cc_nombre' => 'CATAMAYO NORTE']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D02C03', 'di_codigo' => '11D02', 'cc_nombre' => 'CATAMAYO SAN JOSE']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D02C04', 'di_codigo' => '11D02', 'cc_nombre' => 'GUAYQUICHUMA']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D02C05', 'di_codigo' => '11D02', 'cc_nombre' => 'SAN PEDRO DE LA BENDITA']);
        DB::table('circuitos')->insertOrIgnore(['cc_codigo' => '11D02C06', 'di_codigo' => '11D02', 'cc_nombre' => 'CHAGUARPAMB A']);

        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C01S01', 'cc_codigo' => '11D01C01', 'sc_nombre' => 'VILCABAMBA 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C02S01', 'cc_codigo' => '11D01C02', 'sc_nombre' => 'YANGANA 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C03S01', 'cc_codigo' => '11D01C03', 'sc_nombre' => 'MALACATOS 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C04S01', 'cc_codigo' => '11D01C04', 'sc_nombre' => 'TAQUIL 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C04S02', 'cc_codigo' => '11D01C04', 'sc_nombre' => 'TAQUIL 2']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C05S01', 'cc_codigo' => '11D01C05', 'sc_nombre' => 'ZAMORA HUAYCO 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C06S01', 'cc_codigo' => '11D01C06', 'sc_nombre' => 'ESTEBAN GODOY 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C06S02', 'cc_codigo' => '11D01C06', 'sc_nombre' => 'ESTEBAN GODOY 2']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C07S01', 'cc_codigo' => '11D01C07', 'sc_nombre' => 'EL PARAISO 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C08S01', 'cc_codigo' => '11D01C08', 'sc_nombre' => 'CELI ROMAN 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C09S01', 'cc_codigo' => '11D01C09', 'sc_nombre' => 'IV CENTENARIO 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C10S01', 'cc_codigo' => '11D01C10', 'sc_nombre' => 'TEBAIDA 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C11S01', 'cc_codigo' => '11D01C11', 'sc_nombre' => 'LOS MOLINOS 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D01C12S01', 'cc_codigo' => '11D01C12', 'sc_nombre' => 'CHONTACRUZ 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D02C01S01', 'cc_codigo' => '11D02C01', 'sc_nombre' => 'EL TAMBO 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D02C02S01', 'cc_codigo' => '11D02C02', 'sc_nombre' => 'CATAMAYO NORTE 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D02C02S02', 'cc_codigo' => '11D02C02', 'sc_nombre' => 'CATAMAYO NORTE 2']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D02C03S01', 'cc_codigo' => '11D02C03', 'sc_nombre' => 'CATAMAYO SAN JOSE 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D02C04S01', 'cc_codigo' => '11D02C04', 'sc_nombre' => 'GUAYQUICHUMA 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D02C05S01', 'cc_codigo' => '11D02C05', 'sc_nombre' => 'SAN PEDRO DE LA BENDITA 1']);
        DB::table('subcircuitos')->insertOrIgnore(['sc_codigo' => '11D02C06S01', 'cc_codigo' => '11D02C06', 'sc_nombre' => 'CHAGUARPAMBA 1']);

        /*Geografía*/
        DB::table('paises')->insertOrIgnore(['pa_codigo' => 1, 'pa_nombre' => 'Ecuador']);
        DB::table('paises')->insertOrIgnore(['pa_codigo' => 2, 'pa_nombre' => 'Colombia']);
        DB::table('paises')->insertOrIgnore(['pa_codigo' => 3, 'pa_nombre' => 'China']);
        DB::table('paises')->insertOrIgnore(['pa_codigo' => 3, 'pa_nombre' => 'Estados Unidos']);
        DB::table('paises')->insertOrIgnore(['pa_codigo' => 4, 'pa_nombre' => 'Francia']);
        DB::table('paises')->insertOrIgnore(['pa_codigo' => 5, 'pa_nombre' => 'Alemania']);

        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 1, 'pa_codigo' => 1, 'pr_nombre' => 'Azuay']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 2, 'pa_codigo' => 1, 'pr_nombre' => 'Bolívar']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 3, 'pa_codigo' => 1, 'pr_nombre' => 'Cañar']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 4, 'pa_codigo' => 1, 'pr_nombre' => 'Carchi']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 5, 'pa_codigo' => 1, 'pr_nombre' => 'Chimborazo']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 6, 'pa_codigo' => 1, 'pr_nombre' => 'Cotopaxi']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 7, 'pa_codigo' => 1, 'pr_nombre' => 'El Oro']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 8, 'pa_codigo' => 1, 'pr_nombre' => 'Esmeraldas']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 9, 'pa_codigo' => 1, 'pr_nombre' => 'Galápagos']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 10, 'pa_codigo' => 1, 'pr_nombre' => 'Guayas']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 11, 'pa_codigo' => 1, 'pr_nombre' => 'Imbabura']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 12, 'pa_codigo' => 1, 'pr_nombre' => 'Loja']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 13, 'pa_codigo' => 1, 'pr_nombre' => 'Los Ríos']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 14, 'pa_codigo' => 1, 'pr_nombre' => 'Manabí']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 15, 'pa_codigo' => 1, 'pr_nombre' => 'Morona Santiago']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 16, 'pa_codigo' => 1, 'pr_nombre' => 'Napo']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 17, 'pa_codigo' => 1, 'pr_nombre' => 'Orellana']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 18, 'pa_codigo' => 1, 'pr_nombre' => 'Pastaza']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 19, 'pa_codigo' => 1, 'pr_nombre' => 'Pichincha']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 20, 'pa_codigo' => 1, 'pr_nombre' => 'Santa Elena']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 21, 'pa_codigo' => 1, 'pr_nombre' => 'Santo Domingo de los Tsáchilas']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 22, 'pa_codigo' => 1, 'pr_nombre' => 'Sucumbíos']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 23, 'pa_codigo' => 1, 'pr_nombre' => 'Tungurahua']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 24, 'pa_codigo' => 1, 'pr_nombre' => 'Zamora Chinchipe']);

        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 1, 'pr_codigo' => 17, 'ci_nombre' => 'Aguarico']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 2, 'pr_codigo' => 5, 'ci_nombre' => 'Alausí']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 3, 'pr_codigo' => 10, 'ci_nombre' => 'Alfredo Baquerizo Moreno']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 4, 'pr_codigo' => 23, 'ci_nombre' => 'Ambato']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 5, 'pr_codigo' => 11, 'ci_nombre' => 'Antonio Ante']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 6, 'pr_codigo' => 18, 'ci_nombre' => 'Arajuno']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 7, 'pr_codigo' => 16, 'ci_nombre' => 'Archidona']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 8, 'pr_codigo' => 7, 'ci_nombre' => 'Arenillas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 9, 'pr_codigo' => 8, 'ci_nombre' => 'Atacames']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 10, 'pr_codigo' => 7, 'ci_nombre' => 'Atahualpa']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 11, 'pr_codigo' => 3, 'ci_nombre' => 'Azogues']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 12, 'pr_codigo' => 13, 'ci_nombre' => 'Baba']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 13, 'pr_codigo' => 13, 'ci_nombre' => 'Babahoyo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 14, 'pr_codigo' => 10, 'ci_nombre' => 'Balao']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 15, 'pr_codigo' => 7, 'ci_nombre' => 'Balsas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 16, 'pr_codigo' => 10, 'ci_nombre' => 'Balzar']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 17, 'pr_codigo' => 23, 'ci_nombre' => 'Baños']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 18, 'pr_codigo' => 3, 'ci_nombre' => 'Biblián']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 19, 'pr_codigo' => 4, 'ci_nombre' => 'Bolívar (Carchi)']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 20, 'pr_codigo' => 14, 'ci_nombre' => 'Bolívar (Manabí)']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 21, 'pr_codigo' => 13, 'ci_nombre' => 'Buena Fe']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 22, 'pr_codigo' => 2, 'ci_nombre' => 'Caluma']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 23, 'pr_codigo' => 12, 'ci_nombre' => 'Calvas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 24, 'pr_codigo' => 1, 'ci_nombre' => 'Camilo Ponce Enríquez']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 25, 'pr_codigo' => 3, 'ci_nombre' => 'Cañar']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 26, 'pr_codigo' => 16, 'ci_nombre' => 'Carlos Julio Arosemena Tola']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 27, 'pr_codigo' => 22, 'ci_nombre' => 'Cascales']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 28, 'pr_codigo' => 12, 'ci_nombre' => 'Catamayo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 29, 'pr_codigo' => 19, 'ci_nombre' => 'Cayambe']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 30, 'pr_codigo' => 12, 'ci_nombre' => 'Celica']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 31, 'pr_codigo' => 24, 'ci_nombre' => 'Centinela del Cóndor']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 32, 'pr_codigo' => 23, 'ci_nombre' => 'Cevallos']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 33, 'pr_codigo' => 12, 'ci_nombre' => 'Chaguarpamba']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 34, 'pr_codigo' => 5, 'ci_nombre' => 'Chambo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 35, 'pr_codigo' => 7, 'ci_nombre' => 'Chilla']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 36, 'pr_codigo' => 2, 'ci_nombre' => 'Chillanes']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 37, 'pr_codigo' => 2, 'ci_nombre' => 'Chimbo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 38, 'pr_codigo' => 24, 'ci_nombre' => 'Chinchipe']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 39, 'pr_codigo' => 14, 'ci_nombre' => 'Chone']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 40, 'pr_codigo' => 1, 'ci_nombre' => 'Chordeleg']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 41, 'pr_codigo' => 5, 'ci_nombre' => 'Chunchi']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 42, 'pr_codigo' => 10, 'ci_nombre' => 'Colimes']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 43, 'pr_codigo' => 5, 'ci_nombre' => 'Colta']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 44, 'pr_codigo' => 10, 'ci_nombre' => 'Coronel Marcelino Maridueña']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 45, 'pr_codigo' => 11, 'ci_nombre' => 'Cotacachi']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 46, 'pr_codigo' => 1, 'ci_nombre' => 'Cuenca']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 47, 'pr_codigo' => 5, 'ci_nombre' => 'Cumandá']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 48, 'pr_codigo' => 22, 'ci_nombre' => 'Cuyabeno']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 49, 'pr_codigo' => 10, 'ci_nombre' => 'Daule']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 50, 'pr_codigo' => 3, 'ci_nombre' => 'Déleg']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 51, 'pr_codigo' => 19, 'ci_nombre' => 'Distrito Metropolitano de Quito']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 52, 'pr_codigo' => 10, 'ci_nombre' => 'Durán']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 53, 'pr_codigo' => 2, 'ci_nombre' => 'Echeandía']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 54, 'pr_codigo' => 14, 'ci_nombre' => 'El Carmen']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 55, 'pr_codigo' => 16, 'ci_nombre' => 'El Chaco']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 56, 'pr_codigo' => 10, 'ci_nombre' => 'El Empalme']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 57, 'pr_codigo' => 7, 'ci_nombre' => 'El Guabo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 58, 'pr_codigo' => 1, 'ci_nombre' => 'El Pan']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 59, 'pr_codigo' => 24, 'ci_nombre' => 'El Pangui']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 60, 'pr_codigo' => 3, 'ci_nombre' => 'El Tambo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 61, 'pr_codigo' => 10, 'ci_nombre' => 'El Triunfo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 62, 'pr_codigo' => 8, 'ci_nombre' => 'Eloy Alfaro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 63, 'pr_codigo' => 8, 'ci_nombre' => 'Esmeraldas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 64, 'pr_codigo' => 4, 'ci_nombre' => 'Espejo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 65, 'pr_codigo' => 12, 'ci_nombre' => 'Espíndola']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 66, 'pr_codigo' => 14, 'ci_nombre' => 'Flavio Alfaro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 67, 'pr_codigo' => 17, 'ci_nombre' => 'Francisco de Orellana']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 68, 'pr_codigo' => 10, 'ci_nombre' => 'General Antonio Elizalde']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 69, 'pr_codigo' => 1, 'ci_nombre' => 'Girón']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 70, 'pr_codigo' => 22, 'ci_nombre' => 'Gonzalo Pizarro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 71, 'pr_codigo' => 12, 'ci_nombre' => 'Gonzanamá']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 72, 'pr_codigo' => 1, 'ci_nombre' => 'Guachapala']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 73, 'pr_codigo' => 1, 'ci_nombre' => 'Gualaceo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 74, 'pr_codigo' => 15, 'ci_nombre' => 'Gualaquiza']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 75, 'pr_codigo' => 5, 'ci_nombre' => 'Guamote']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 76, 'pr_codigo' => 5, 'ci_nombre' => 'Guano']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 77, 'pr_codigo' => 2, 'ci_nombre' => 'Guaranda']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 78, 'pr_codigo' => 10, 'ci_nombre' => 'Guayaquil']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 79, 'pr_codigo' => 15, 'ci_nombre' => 'Huamboya']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 80, 'pr_codigo' => 7, 'ci_nombre' => 'Huaquillas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 81, 'pr_codigo' => 11, 'ci_nombre' => 'Ibarra']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 82, 'pr_codigo' => 9, 'ci_nombre' => 'Isabela']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 83, 'pr_codigo' => 10, 'ci_nombre' => 'Isidro Ayora']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 84, 'pr_codigo' => 14, 'ci_nombre' => 'Jama']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 85, 'pr_codigo' => 14, 'ci_nombre' => 'Jaramijó']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 86, 'pr_codigo' => 14, 'ci_nombre' => 'Jipijapa']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 87, 'pr_codigo' => 14, 'ci_nombre' => 'Junín']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 88, 'pr_codigo' => 21, 'ci_nombre' => 'La Concordia']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 89, 'pr_codigo' => 17, 'ci_nombre' => 'La Joya de los Sachas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 90, 'pr_codigo' => 20, 'ci_nombre' => 'La Libertad']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 91, 'pr_codigo' => 6, 'ci_nombre' => 'La Maná']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 92, 'pr_codigo' => 3, 'ci_nombre' => 'La Troncal']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 93, 'pr_codigo' => 22, 'ci_nombre' => 'Lago Agrio']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 94, 'pr_codigo' => 7, 'ci_nombre' => 'Las Lajas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 95, 'pr_codigo' => 2, 'ci_nombre' => 'Las Naves']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 96, 'pr_codigo' => 6, 'ci_nombre' => 'Latacunga']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 97, 'pr_codigo' => 15, 'ci_nombre' => 'Limón Indanza']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 98, 'pr_codigo' => 15, 'ci_nombre' => 'Logroño']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 99, 'pr_codigo' => 12, 'ci_nombre' => 'Loja']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 100, 'pr_codigo' => 10, 'ci_nombre' => 'Lomas de Sargentillo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 101, 'pr_codigo' => 17, 'ci_nombre' => 'Loreto']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 102, 'pr_codigo' => 12, 'ci_nombre' => 'Macará']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 103, 'pr_codigo' => 7, 'ci_nombre' => 'Machala']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 104, 'pr_codigo' => 14, 'ci_nombre' => 'Manta']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 105, 'pr_codigo' => 7, 'ci_nombre' => 'Marcabelí']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 106, 'pr_codigo' => 19, 'ci_nombre' => 'Mejía']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 107, 'pr_codigo' => 18, 'ci_nombre' => 'Mera']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 108, 'pr_codigo' => 10, 'ci_nombre' => 'Milagro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 109, 'pr_codigo' => 4, 'ci_nombre' => 'Mira']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 110, 'pr_codigo' => 13, 'ci_nombre' => 'Mocache']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 111, 'pr_codigo' => 23, 'ci_nombre' => 'Mocha']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 112, 'pr_codigo' => 13, 'ci_nombre' => 'Montalvo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 113, 'pr_codigo' => 14, 'ci_nombre' => 'Montecristi']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 114, 'pr_codigo' => 4, 'ci_nombre' => 'Montúfar']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 115, 'pr_codigo' => 15, 'ci_nombre' => 'Morona']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 116, 'pr_codigo' => 8, 'ci_nombre' => 'Muisne']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 117, 'pr_codigo' => 1, 'ci_nombre' => 'Nabón']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 118, 'pr_codigo' => 24, 'ci_nombre' => 'Nangaritza']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 119, 'pr_codigo' => 10, 'ci_nombre' => 'Naranjal']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 120, 'pr_codigo' => 10, 'ci_nombre' => 'Naranjito']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 121, 'pr_codigo' => 10, 'ci_nombre' => 'Nobol']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 122, 'pr_codigo' => 12, 'ci_nombre' => 'Olmedo (Loja)']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 123, 'pr_codigo' => 14, 'ci_nombre' => 'Olmedo (Manabí)']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 124, 'pr_codigo' => 1, 'ci_nombre' => 'Oña']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 125, 'pr_codigo' => 11, 'ci_nombre' => 'Otavalo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 126, 'pr_codigo' => 15, 'ci_nombre' => 'Pablo Sexto']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 127, 'pr_codigo' => 14, 'ci_nombre' => 'Paján']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 128, 'pr_codigo' => 24, 'ci_nombre' => 'Palanda']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 129, 'pr_codigo' => 13, 'ci_nombre' => 'Palenque']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 130, 'pr_codigo' => 10, 'ci_nombre' => 'Palestina']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 131, 'pr_codigo' => 5, 'ci_nombre' => 'Pallatanga']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 132, 'pr_codigo' => 15, 'ci_nombre' => 'Palora']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 133, 'pr_codigo' => 12, 'ci_nombre' => 'Paltas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 134, 'pr_codigo' => 6, 'ci_nombre' => 'Pangua']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 135, 'pr_codigo' => 24, 'ci_nombre' => 'Paquisha']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 136, 'pr_codigo' => 7, 'ci_nombre' => 'Pasaje']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 137, 'pr_codigo' => 18, 'ci_nombre' => 'Pastaza']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 138, 'pr_codigo' => 23, 'ci_nombre' => 'Patate']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 139, 'pr_codigo' => 1, 'ci_nombre' => 'Paute']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 140, 'pr_codigo' => 14, 'ci_nombre' => 'Pedernales']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 141, 'pr_codigo' => 10, 'ci_nombre' => 'Pedro Carbo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 142, 'pr_codigo' => 19, 'ci_nombre' => 'Pedro Moncayo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 143, 'pr_codigo' => 19, 'ci_nombre' => 'Pedro Vicente Maldonado']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 144, 'pr_codigo' => 23, 'ci_nombre' => 'Pelileo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 145, 'pr_codigo' => 5, 'ci_nombre' => 'Penipe']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 146, 'pr_codigo' => 14, 'ci_nombre' => 'Pichincha']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 147, 'pr_codigo' => 23, 'ci_nombre' => 'Píllaro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 148, 'pr_codigo' => 11, 'ci_nombre' => 'Pimampiro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 149, 'pr_codigo' => 7, 'ci_nombre' => 'Piñas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 150, 'pr_codigo' => 12, 'ci_nombre' => 'Pindal']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 151, 'pr_codigo' => 10, 'ci_nombre' => 'Playas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 152, 'pr_codigo' => 7, 'ci_nombre' => 'Portovelo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 153, 'pr_codigo' => 14, 'ci_nombre' => 'Portoviejo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 154, 'pr_codigo' => 1, 'ci_nombre' => 'Pucará']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 155, 'pr_codigo' => 13, 'ci_nombre' => 'Puebloviejo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 156, 'pr_codigo' => 14, 'ci_nombre' => 'Puerto López']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 157, 'pr_codigo' => 19, 'ci_nombre' => 'Puerto Quito']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 158, 'pr_codigo' => 6, 'ci_nombre' => 'Pujilí']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 159, 'pr_codigo' => 22, 'ci_nombre' => 'Putumayo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 160, 'pr_codigo' => 12, 'ci_nombre' => 'Puyango']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 161, 'pr_codigo' => 23, 'ci_nombre' => 'Quero']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 162, 'pr_codigo' => 13, 'ci_nombre' => 'Quevedo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 163, 'pr_codigo' => 16, 'ci_nombre' => 'Quijos']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 164, 'pr_codigo' => 12, 'ci_nombre' => 'Quilanga']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 165, 'pr_codigo' => 8, 'ci_nombre' => 'Quinindé']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 166, 'pr_codigo' => 13, 'ci_nombre' => 'Quinsaloma']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 167, 'pr_codigo' => 5, 'ci_nombre' => 'Riobamba']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 168, 'pr_codigo' => 8, 'ci_nombre' => 'Rioverde']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 169, 'pr_codigo' => 14, 'ci_nombre' => 'Rocafuerte']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 170, 'pr_codigo' => 19, 'ci_nombre' => 'Rumiñahui']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 171, 'pr_codigo' => 6, 'ci_nombre' => 'Salcedo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 172, 'pr_codigo' => 20, 'ci_nombre' => 'Salinas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 173, 'pr_codigo' => 10, 'ci_nombre' => 'Salitre']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 174, 'pr_codigo' => 10, 'ci_nombre' => 'Samborondón']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 175, 'pr_codigo' => 9, 'ci_nombre' => 'San Cristóbal']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 176, 'pr_codigo' => 1, 'ci_nombre' => 'San Fernando']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 177, 'pr_codigo' => 15, 'ci_nombre' => 'San Juan Bosco']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 178, 'pr_codigo' => 8, 'ci_nombre' => 'San Lorenzo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 179, 'pr_codigo' => 2, 'ci_nombre' => 'San Miguel']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 180, 'pr_codigo' => 19, 'ci_nombre' => 'San Miguel de Los Bancos']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 181, 'pr_codigo' => 11, 'ci_nombre' => 'San Miguel de Urcuquí']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 182, 'pr_codigo' => 4, 'ci_nombre' => 'San Pedro de Huaca']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 183, 'pr_codigo' => 14, 'ci_nombre' => 'San Vicente']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 184, 'pr_codigo' => 14, 'ci_nombre' => 'Santa Ana']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 185, 'pr_codigo' => 18, 'ci_nombre' => 'Santa Clara']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 186, 'pr_codigo' => 9, 'ci_nombre' => 'Santa Cruz']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 187, 'pr_codigo' => 20, 'ci_nombre' => 'Santa Elena']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 188, 'pr_codigo' => 1, 'ci_nombre' => 'Santa Isabel']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 189, 'pr_codigo' => 10, 'ci_nombre' => 'Santa Lucía']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 190, 'pr_codigo' => 7, 'ci_nombre' => 'Santa Rosa']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 191, 'pr_codigo' => 15, 'ci_nombre' => 'Santiago']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 192, 'pr_codigo' => 21, 'ci_nombre' => 'Santo Domingo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 193, 'pr_codigo' => 6, 'ci_nombre' => 'Saquisilí']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 194, 'pr_codigo' => 12, 'ci_nombre' => 'Saraguro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 195, 'pr_codigo' => 1, 'ci_nombre' => 'Sevilla de Oro']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 196, 'pr_codigo' => 22, 'ci_nombre' => 'Shushufindi']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 197, 'pr_codigo' => 6, 'ci_nombre' => 'Sigchos']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 198, 'pr_codigo' => 1, 'ci_nombre' => 'Sígsig']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 199, 'pr_codigo' => 10, 'ci_nombre' => 'Simón Bolívar']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 200, 'pr_codigo' => 12, 'ci_nombre' => 'Sozoranga']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 201, 'pr_codigo' => 14, 'ci_nombre' => 'Sucre']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 202, 'pr_codigo' => 15, 'ci_nombre' => 'Sucúa']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 203, 'pr_codigo' => 22, 'ci_nombre' => 'Sucumbíos']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 204, 'pr_codigo' => 3, 'ci_nombre' => 'Suscal']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 205, 'pr_codigo' => 15, 'ci_nombre' => 'Taisha']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 206, 'pr_codigo' => 16, 'ci_nombre' => 'Tena']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 207, 'pr_codigo' => 23, 'ci_nombre' => 'Tisaleo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 208, 'pr_codigo' => 15, 'ci_nombre' => 'Tiwintza']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 209, 'pr_codigo' => 14, 'ci_nombre' => 'Tosagua']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 210, 'pr_codigo' => 4, 'ci_nombre' => 'Tulcán']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 211, 'pr_codigo' => 13, 'ci_nombre' => 'Urdaneta']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 212, 'pr_codigo' => 13, 'ci_nombre' => 'Valencia']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 213, 'pr_codigo' => 14, 'ci_nombre' => 'Veinticuatro de Mayo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 214, 'pr_codigo' => 13, 'ci_nombre' => 'Ventanas']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 215, 'pr_codigo' => 13, 'ci_nombre' => 'Vinces']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 216, 'pr_codigo' => 24, 'ci_nombre' => 'Yacuambi']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 217, 'pr_codigo' => 10, 'ci_nombre' => 'Yaguachi']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 218, 'pr_codigo' => 24, 'ci_nombre' => 'Yantzaza']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 219, 'pr_codigo' => 24, 'ci_nombre' => 'Zamora']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 220, 'pr_codigo' => 12, 'ci_nombre' => 'Zapotillo']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 221, 'pr_codigo' => 7, 'ci_nombre' => 'Zaruma']);

        /*Exterior*/
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 25, 'pa_codigo' => 2, 'pr_nombre' => 'Valle del Cauca']);
        DB::table('provincias')->insertOrIgnore(['pr_codigo' => 26, 'pa_codigo' => 2, 'pr_nombre' => 'Antioquia']);

        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 222, 'pr_codigo' => 25, 'ci_nombre' => 'Cali']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 223, 'pr_codigo' => 25, 'ci_nombre' => 'El Cerrito']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 224, 'pr_codigo' => 25, 'ci_nombre' => 'Palmira']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 225, 'pr_codigo' => 25, 'ci_nombre' => 'Cartago']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 226, 'pr_codigo' => 25, 'ci_nombre' => 'Buenaventura']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 227, 'pr_codigo' => 25, 'ci_nombre' => 'Yumbo']);

        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 228, 'pr_codigo' => 26, 'ci_nombre' => 'Medellín']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 229, 'pr_codigo' => 26, 'ci_nombre' => 'Bello']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 230, 'pr_codigo' => 26, 'ci_nombre' => 'Itagüí']);
        DB::table('ciudades')->insertOrIgnore(['ci_codigo' => 231, 'pr_codigo' => 26, 'ci_nombre' => 'Envigado']);

        /*Comunicación*/
        DB::table('tipo_contactos')->insertOrIgnore(['tc_codigo' => 1, 'tc_nombre' => 'Celular', 'tc_min' => 10, 'tc_max' => 10, 'tc_rex' => '^\d{10}$', 'tc_ico' => 'local_phone']);
        DB::table('tipo_contactos')->insertOrIgnore(['tc_codigo' => 2, 'tc_nombre' => 'e-Mail', 'tc_min' => 10, 'tc_max' => 254, 'tc_rex' => '^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$', 'tc_ico' => 'email']);
        DB::table('tipo_contactos')->insertOrIgnore(['tc_codigo' => 3, 'tc_nombre' => 'Dirección', 'tc_min' => 10, 'tc_max' => 1024, 'tc_rex' => '', 'tc_ico' => 'directions']);

        /*Vehículos*/
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 0, 'vt_nombre' => 'Ninguno']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 1, 'vt_nombre' => 'Auto']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 2, 'vt_nombre' => 'Motocicleta']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 3, 'vt_nombre' => 'Camioneta']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 4, 'vt_nombre' => 'Autobús']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 5, 'vt_nombre' => 'Vehículo todo terreno (ATV)']);/*
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 6, 'vt_nombre' => 'Caballo']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 7, 'vt_nombre' => 'Bicicleta']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 8, 'vt_nombre' => 'Bote']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 9, 'vt_nombre' => 'Aeronave']);
        DB::table('vehiculo_tipos')->insertOrIgnore(['vt_codigo' => 10, 'vt_nombre' => 'Scooter']);*/

        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 0, 'vm_nombre' => 'Ninguno']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 1, 'vm_nombre' => 'Audi']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 2, 'vm_nombre' => 'BMW']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 3, 'vm_nombre' => 'Chevrolet']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 4, 'vm_nombre' => 'Citroën']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 5, 'vm_nombre' => 'Dodge']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 6, 'vm_nombre' => 'Fiat']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 7, 'vm_nombre' => 'Ford']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 8, 'vm_nombre' => 'Harley-Davidson']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 9, 'vm_nombre' => 'Honda']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 10, 'vm_nombre' => 'Hyundai']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 11, 'vm_nombre' => 'Jeep']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 12, 'vm_nombre' => 'Kia']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 13, 'vm_nombre' => 'Mazda']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 14, 'vm_nombre' => 'Mercedes-Benz']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 15, 'vm_nombre' => 'Mitsubishi']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 16, 'vm_nombre' => 'Nissan']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 17, 'vm_nombre' => 'Opel']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 18, 'vm_nombre' => 'Peugeot']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 19, 'vm_nombre' => 'Ram']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 20, 'vm_nombre' => 'Renault']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 21, 'vm_nombre' => 'Subaru']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 22, 'vm_nombre' => 'Suzuki']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 23, 'vm_nombre' => 'Toyota']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 24, 'vm_nombre' => 'Volkswagen']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 25, 'vm_nombre' => 'Volvo']);
        DB::table('vehiculo_marcas')->insertOrIgnore(['vm_codigo' => 26, 'vm_nombre' => 'Yamaha']);

        DB::table('vehiculo_modelos')->insertOrIgnore(['mm_codigo' => 0, 'vm_codigo' => 0, 'mm_nombre' => 'Ninguno']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 1, 'mm_nombre' => 'A3']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 1, 'mm_nombre' => 'A4']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 1, 'mm_nombre' => 'Q3']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 2, 'mm_nombre' => '3 Series']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 2, 'mm_nombre' => 'R 1200 RT']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 2, 'mm_nombre' => 'X1']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Aveo']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Blazer']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Captiva']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Cruze']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Equinox']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Express']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Impala']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Malibu']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Onix']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Prisma']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'S-10']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Silverado']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 3, 'mm_nombre' => 'Spin']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 4, 'mm_nombre' => 'C3']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 4, 'mm_nombre' => 'C4']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 4, 'mm_nombre' => 'C4 Cactus']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 5, 'mm_nombre' => 'Grand Siena']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 5, 'mm_nombre' => 'Journey']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 6, 'mm_nombre' => 'Argo']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 6, 'mm_nombre' => 'Cronos']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 6, 'mm_nombre' => 'Ducato']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 6, 'mm_nombre' => 'Mobi']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 6, 'mm_nombre' => 'Pulse']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 6, 'mm_nombre' => 'Toro']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 7, 'mm_nombre' => 'Ecosport']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 7, 'mm_nombre' => 'Escape']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 7, 'mm_nombre' => 'F-150']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 7, 'mm_nombre' => 'Fiesta']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 7, 'mm_nombre' => 'Ka']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 7, 'mm_nombre' => 'Ranger']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 8, 'mm_nombre' => 'Electra Glide']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 9, 'mm_nombre' => 'City']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 9, 'mm_nombre' => 'Civic']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 9, 'mm_nombre' => 'CR-V']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 9, 'mm_nombre' => 'Gold Wing']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 9, 'mm_nombre' => 'HR-V']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 9, 'mm_nombre' => 'Ridgeline']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 10, 'mm_nombre' => 'Creta']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 10, 'mm_nombre' => 'HB20']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 10, 'mm_nombre' => 'Santa Cruz']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 10, 'mm_nombre' => 'Tucson']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 11, 'mm_nombre' => 'Compass']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 11, 'mm_nombre' => 'Renegade']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 12, 'mm_nombre' => 'Rio']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 12, 'mm_nombre' => 'Seltos']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 12, 'mm_nombre' => 'Sportage']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 13, 'mm_nombre' => '3']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 13, 'mm_nombre' => 'CX-5']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 14, 'mm_nombre' => 'CLA']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 14, 'mm_nombre' => 'GLA']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 15, 'mm_nombre' => 'Eclipse Cross']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 15, 'mm_nombre' => 'Pajero']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 16, 'mm_nombre' => 'Frontier']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 16, 'mm_nombre' => 'Kicks']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 16, 'mm_nombre' => 'March']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 16, 'mm_nombre' => 'Rogue']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 16, 'mm_nombre' => 'Versa']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 17, 'mm_nombre' => 'Corsa']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 17, 'mm_nombre' => 'Crossland']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 17, 'mm_nombre' => 'Cruze']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 18, 'mm_nombre' => '208']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 18, 'mm_nombre' => '308']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 18, 'mm_nombre' => '2008']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 19, 'mm_nombre' => '1500']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 20, 'mm_nombre' => 'Alaskan']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 20, 'mm_nombre' => 'Captur']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 20, 'mm_nombre' => 'Duster']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 20, 'mm_nombre' => 'Kwid']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 20, 'mm_nombre' => 'Logan']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 20, 'mm_nombre' => 'Master']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 20, 'mm_nombre' => 'Sandero']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 21, 'mm_nombre' => 'Forester']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 22, 'mm_nombre' => 'Swift']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 23, 'mm_nombre' => 'Coaster']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 23, 'mm_nombre' => 'Corolla']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 23, 'mm_nombre' => 'Corolla Cross']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 23, 'mm_nombre' => 'Etios']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 23, 'mm_nombre' => 'Hilux']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 23, 'mm_nombre' => 'RAV4']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 23, 'mm_nombre' => 'Yaris']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 24, 'mm_nombre' => 'Amarok']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 24, 'mm_nombre' => 'Crafter']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 24, 'mm_nombre' => 'Gol']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 24, 'mm_nombre' => 'Jetta']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 24, 'mm_nombre' => 'Nivus']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 24, 'mm_nombre' => 'Polo']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 25, 'mm_nombre' => 'XC40']);
        DB::table('vehiculo_modelos')->insertOrIgnore(['vm_codigo' => 26, 'mm_nombre' => 'V-Strom 1000']);

        /*Contratos*/
        DB::table('contrato_tipos')->insertOrIgnore(['kt_codigo' => 0, 'kt_nombre' => 'Ninguno']);
        DB::table('contrato_tipos')->insertOrIgnore(['kt_codigo' => 1, 'kt_nombre' => 'Mantenimiento Vehicular']);
        DB::table('contrato_tipos')->insertOrIgnore(['kt_codigo' => 2, 'kt_nombre' => 'Abastecimiento de combustible']);


    }
}
