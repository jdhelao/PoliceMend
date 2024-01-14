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
    sv_observacion?: string;
    sv_estado?: boolean;

    /*Orden mantenimiento/abastecimiento*/
    id_orden?: number;
    om_codigo?: number;
    oa_codigo?: number;
    orden_progreso?: number;

    /*Auditoria*/
    us_codigo?: number;

    /*Descripciones*/
    kt_nombre?: string;
    pe_nombres?: string;
    ve_placa?: string;
    ve_color?: string;
    ve_combustible?: string;
    pa_nombre?: string;
    vm_nombre?: string;
    vt_nombre?: string;
    pe_dni?: string;
}
