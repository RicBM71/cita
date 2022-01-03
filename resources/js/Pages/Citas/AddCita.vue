<template>
    <v-col cols="12" md="3" lg="3">
        <v-card max-width="450" class="mx-auto">
            <v-toolbar dense color="cyan lighten-5">
                <v-toolbar-title>Nueva cita</v-toolbar-title>
            </v-toolbar>
            <v-card-text v-if="!edita" class="d-flex justify-center">
                <v-container>
                    <v-row>
                        <v-col cols="12" class="d-flex justify-center">
                            <v-btn v-if="!area.bloqueado" class="blue--text" large outlined rounded @click="goCreate"
                                ><v-icon>mdi-plus</v-icon>Solicitar Cita</v-btn
                            >
                            <v-alert v-else outlined type="info"
                                >Solicitud de citas interrumpido.<br />
                                Prueba en {{ area.demora }} minutos</v-alert
                            >
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <h4>* Si requieres de una cita antes del {{ getDate(start) }} contacta telefónicamente o por WhatsApp.</h4>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-text v-else>
                <v-progress-linear v-if="loading" indeterminate color="teal darken-4"></v-progress-linear>
                <v-row>
                    <v-col cols="6" lg="6" md="8" lg-offset="1">
                        <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="computedDateFormatted"
                                    label="Fecha"
                                    persistent-hint
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="form.fecha"
                                :min="start"
                                :max="end"
                                locale="es"
                                first-day-of-week="1"
                                no-title
                                @input="menu = false"
                                @change="loadHoras"
                                :error-messages="form.errors.fecha"
                            ></v-date-picker>
                        </v-menu>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="6" lg="5" md="4" lg-offset="1">
                        <v-select v-model="turno" :items="turnos" label="Turno" @change="loadHoras"></v-select>
                    </v-col>
                    <v-col v-if="horas.length > 0" cols="5" lg="4" md="4">
                        <v-select v-model="form.hora" :items="horas" label="Hora" :error-messages="form.errors.hora"></v-select>
                    </v-col>
                    <v-col v-else cols="6" lg="6" md="6" class="mt-6">
                        <v-icon color="red">mdi-cancel</v-icon>
                        <span>{{ message }}</span>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" lg="10" md="8" lg-offset="1">
                        <v-select v-model="form.tratamiento_id" :items="tratamientos" label="Tratamiento"></v-select>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="6" lg="4" md="6">
                        <v-btn @click="edita = !edita" color="primary" outlined rounded>Cancelar</v-btn>
                    </v-col>
                    <v-col cols="6" lg="4" md="6">
                        <v-btn @click="store" :disabled="!horas.length" color="primary" outlined rounded>Aceptar</v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
export default {
    props: {
        tratamientos: {
            type: Array,
            required: true,
        },
        ult_tratamiento_id: {
            type: Number,
            required: true,
        },
        fecha_min: {
            type: String,
            required: true,
        },
        fecha_max: {
            type: String,
            required: true,
        },
        area: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            turnos: [
                { text: 'Mañana', value: 'M' },
                { text: 'Tarde', value: 'T' },
            ],
            turno: 'M',
            horas: [],
            menu: false,
            edita: false,
            start: null,
            end: null,
            loading: true,
            message: null,
            form: this.$inertia.form({
                hora: null,
                fecha: null, //new Date().toISOString().substr(0, 10),
                tratamiento_id: null,
                facultativo_id: null,
            }),
        };
    },
    mounted() {
        this.start = this.fecha_min.substr(0, 10);
        this.end = this.fecha_max.substr(0, 10);
        this.form.fecha = this.start;
        this.loadHoras();
    },
    computed: {
        computedDateFormatted() {
            return this.formatDate(this.form.fecha);
        },
    },
    methods: {
        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split('-');
            return `${day}/${month}/${year}`;
        },
        goCreate() {
            this.form.tratamiento_id =
                this.tratamientos.findIndex((t) => t.value == this.ult_tratamiento_id) > 0
                    ? this.ult_tratamiento_id
                    : this.tratamientos[0].value;

            this.form.fecha = this.fecha_min.substr(0, 10);
            this.edita = true;
        },
        loadHoras() {
            this.loading = true;
            axios
                .get(route('book', { fecha: this.form.fecha, turno: this.turno }))
                .then((res) => {
                    this.horas = res.data.horas_libres;
                    this.message = res.data.msg;

                    this.form.facultativo_id = res.data.facultativo_id;
                    this.form.hora = this.horas.length > 0 ? this.horas[0].value : null;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        store() {
            this.loading = true;

            this.form.post(route('book.store'), {
                preserveScroll: true,
                onSuccess: (page) => {
                    this.$toast.success(page.props.flash.success);
                },
                onFinish: () => {
                    this.loading = false;
                },
            });
        },
    },
};
</script>

<style></style>
