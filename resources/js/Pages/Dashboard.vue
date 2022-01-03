<template>
    <v-row>
        <cita
            v-if="$page.props.paciente.data.ultima_cita.estado_id == 1"
            :ultima_cita="$page.props.paciente.data.ultima_cita"
            :nom_ape="$page.props.paciente.data.nom_ape"
        ></cita>
        <add-cita
            v-else
            :tratamientos="$page.props.paciente.data.tratamientos"
            :ult_tratamiento_id="$page.props.paciente.data.tratamiento_id"
            :fecha_min="$page.props.paciente.data.fecha_min"
            :fecha_max="$page.props.paciente.data.fecha_max"
            :area="$page.props.paciente.data.area"
        ></add-cita>

        <bonos v-if="$page.props.paciente.data.bonos != false" :bonos="$page.props.paciente.data.bonos"></bonos>
        <ultimas-citas v-if="$page.props.paciente.data.citas.length > 0" :citas="$page.props.paciente.data.citas"></ultimas-citas>
        <contacto></contacto>
    </v-row>
</template>

<script>
import Contacto from './Citas/Contacto';
import UltimasCitas from './Citas/UltimasCitas';
import Bonos from './Citas/Bonos';
import Cita from './Citas/Cita';
import AddCita from './Citas/AddCita';
export default {
    components: {
        Contacto,
        UltimasCitas,
        Bonos,
        Cita,
        AddCita,
    },
    props: {
        paciente: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            headers: [
                { text: 'Fecha', value: 'fecha', sortable: false },
                { text: 'Hora', value: 'hora', sortable: false },
            ],
            edita: false,
        };
    },
    mounted() {
        console.log(this.$page.props.paciente);
    },
};
</script>
