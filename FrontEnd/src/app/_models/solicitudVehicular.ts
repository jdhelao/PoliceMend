export class SolicitudVehicular {
    sv_codigo?: number;

    kt_codigo?: number;
    pe_codigo?: number;
    ve_codigo?: number;

    ve_km?: number;
    ve_combustible_nivel?: number;

    sv_fecha_requerimiento?: Date;
    sv_descripcion?: string;

    sv_aprobacion?: boolean;
    sv_estado?: boolean;

    kt_nombre?: string;
    pe_nombres?: string;
    ve_placa?: string;

    /*Orden mantenimiento/abastecimiento*/
    om_codigo?: number;
    oa_codigo?: number;

    /*Auditoria*/
    us_codigo?: number;

}
