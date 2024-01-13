export class OrdenMantenimiento {
    om_codigo?: number;

    sv_codigo?: number;
    en_codigo?: number;

    om_total?: number;

    pe_codigo?: number; /* Tecnico asignado */

    om_ingreso_condicion?: string;
    om_ingreso_aceptacion?: boolean;

    om_entrega_condicion?: string;
    om_entrega_aceptacion?: boolean;

    om_progreso?: number;

    om_documento?: string;
    om_archivo_datos?: string;
    om_archivo_tipo?: string;

    om_estado?: boolean;

    /*Solicitud*/
    ve_km?: number;
    ve_combustible_nivel?: number;

    /*Auditoria*/
    us_codigo?: number;

    /*Descripciones*/

}
