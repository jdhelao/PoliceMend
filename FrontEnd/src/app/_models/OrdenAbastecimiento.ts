export class OrdenAbastecimiento {
    oa_codigo?: number;

    sv_codigo?: number;
    en_codigo?: number;

    oa_total?: number;
    oa_galones?: number;
    oa_combustible_nivel?: number;

    oa_documento?: string;
    oa_archivo_datos?: string;
    oa_archivo_tipo?: string;

    oa_estado?: boolean;

    /*Solicitud*/
    ve_km?: number;
    ve_combustible_nivel?: number;

    /*Auditoria*/
    us_codigo?: number;

    /*Descripciones*/

}
