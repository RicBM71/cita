<template>
    <v-card>
        <v-card-title>Condiciones de uso</v-card-title>
        <v-card-text>
            <p>
                En aras a dar cumplimiento al Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016 y
                siguiendo las Recomendaciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D.),
            </p>
            <li>
                Los datos de carácter personal solicitados y facilitados por Usted, son incorporados a un fichero de titularidad privada
                cuyo responsable y único destinatario es Centro de Fisioterapia Sanaval, S.L..
            </li>
            <li>
                Solo serán solicitados aquellos datos estrictamente necesarios para prestar adecuadamente el servicio sanitario, pudiendo
                ser necesario recoger datos de contacto de terceros, tales como representantes legales, tutores, o personas a cargo
                designadas por los mismos.
            </li>
            <li>
                Como profesionales de la sanidad, garantizamos que todos los datos recogidos cuentan con el compromiso de confidencialidad y
                cumplen con las medidas de seguridad establecidas legalmente. Bajo ningún concepto susodichos datos serán cedidos o tratados
                por terceras personas -físicas o jurídicas- sin el previo consentimiento del paciente, tutor o representante legal, salvo en
                aquellos casos en los que fuere imprescindible para la correcta prestación del servicio. Una vez finalizada la relación
                entre la empresa y el paciente, los datos serán archivados y conservados durante un periodo de tiempo mínimo de 10 años
                desde la última visita, tras lo cual, podrán continuar archivados, o en su defecto, serán devueltos íntegramente al paciente
                o autorizado legal, o destruidos por procedimientos seguros que garanticen la confidencialidad de la información sensible.
            </li>
            <li>
                Los datos facilitados serán incluidos en el tratamiento denominado Pacientes de Centro de Fisioterapia Sanaval, S.L., con la
                finalidad de gestionar el tratamiento médico, emitir facturas, gestiones relacionadas con el paciente, contacto, manifiestos
                de consentimiento, etc. Puede ejercitar los derechos de acceso, rectificación, cancelación, limitación, oposición y
                portabilidad, indicándolo por escrito a Centro de Fisioterapia Sanaval, S.L., con domicilio en: Avenida de Betanzos, 10,
                Posterior, 28029 - Madrid (Madrid).
            </li>
            <li>
                Los datos personales facilitados podrán ser cedidos por Centro de Fisioterapia Sanaval, S.L. a las entidades que prestan
                servicios a la misma.
            </li>
            <p>Además de las cláusulas anteriores le solicitamos el consentimiento para:</p>
            <p>
                ❏ ACEPTO que Centro de Fisioterapia Sanaval, S.L. me remita comunicaciones informativas a través de e-mail, SMS, o sistemas
                de mensajería instantánea como Whatsapp, con el objetivo de mantenerme informado/a del desarrollo de las actividades propias
                del servicio contratado, enviarme recordatorios de mis citas, así como remitirme informes relativos a la prestación
                asistencial acordada entre ambas partes.
            </p>
            <p>
                ❏ ACEPTO Y SOLICITO EXPRESAMENTE, la recepción de comunicaciones comerciales por vía electrónica (e-mail, Whatsapp,
                bluetooth, SMS), por parte de Centro de Fisioterapia Sanaval, S.L., sobre productos, servicios, promociones y ofertas de mi
                interés.
            </p>
            <h4>Responsable del tratamiento:</h4>

            <p>
                Sus datos pasarán a formar parte de un fichero titularidad de Centro de Fisioterapia Sanaval, S.L., con CIF/NIF nº:
                B82848417 y domicilio social en: Avenida de Betanzos, 10, Posterior, 28029 - Madrid (Madrid).
            </p>
            <h4>Finalidad del tratamiento:</h4>
            <p>
                Desarrollar y cumplir con las obligaciones previstas en el contrato, o relación jurídico-negocial, que le vincula con Centro
                de Fisioterapia Sanaval, S.L..
            </p>
            <h4>Conservación de datos:</h4>
            <p>Sus datos serán conservados durante el periodo de mínimo de diez años.</p>
            <h4>Legitimación:</h4>
            <p>
                La legitimación para la recogida de sus datos se basa en el contrato suscrito o en su relación jurídico-negocial con Centro
                de Fisioterapia Sanaval, S.L..
            </p>
            <h4>Destinatarios:</h4>
            <p>
                Sus datos no serán cedidos para otras finalidades distintas a las anteriormente descritas, salvo obligación legal, si bien
                podrán ser transmitidos a los proveedores de servicios que estén vinculados por contrato encargo de tratamiento con Centro
                de Fisioterapia Sanaval, S.L..
            </p>
            <h4>Derechos:</h4>
            <p>
                Puede ejercer sus derechos de acceso, rectificación, cancelación, limitación, portabilidad y oposición al tratamiento de sus
                datos cuando se den determinadas circunstancias, en cuyo caso únicamente serán conservados para el cumplimiento de las
                obligaciones legalmente previstas.
            </p>
            <p>
                Para ejercer los derechos anteriormente descritos deberá dirigirse a Centro de Fisioterapia Sanaval, S.L., con CIF/NIF nº:
                B82848417 y domicilio social en: Avenida de Betanzos, 10, Posterior, 28029 - Madrid (Madrid).
            </p>
            <p>
                De igual modo, le informamos de que la Agencia Española de Protección de Datos es el órgano competente destinado a la tutela
                de estos derechos. Con la finalidad de mantener actualizados los datos, el cliente deberá comunicar cualquier cambio que se
                produzca sobre los mismos.
            </p>
            <h4>Compromiso de confidencialidad:</h4>
            <p>
                De igual modo, de acuerdo al artículo 32 del RGPD, relativo al deber de secreto profesional, Centro de Fisioterapia Sanaval,
                S.L. se compromete a guardar la confidencialidad de los datos de carácter personal, subsistiendo esta obligación con
                posterioridad a la finalización, por cualquier causa, de la relación entre Usted y Centro de Fisioterapia Sanaval, S.L.
            </p>
        </v-card-text>
        <v-card-actions class="justify-center">
            <v-btn @click="submit" :loading="loading">Aceptar Términos y Condiciones</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
        return {
            loading: false,
        };
    },
    mounted() {
        console.log(this.$page.props.paciente);
    },
    methods: {
        submit() {
            this.loading = true;
            this.$inertia.put(route('lortad.update'), {
                preserveScroll: true,
            });
        },
    },
};
</script>
