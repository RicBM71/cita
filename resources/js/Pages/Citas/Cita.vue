<template>
    <v-col cols="12" md="3" lg="3">
        <my-dialog :dialog.sync="dialog" @destroyReg="destroyReg"></my-dialog>
        <v-card max-width="450" class="mx-auto">
            <v-toolbar dense color="secondary">
                <v-toolbar-title>Próxima cita</v-toolbar-title>
            </v-toolbar>
            <!-- <v-img v-if="ultima_cita.bono.numero_bono == null" src="/assets/bono3.jpg"> -->
            <v-list-item class="mt-4 text-center">
                <v-list-item-content>
                    <v-list-item-title class="font-weight-bold">
                        <v-icon color="blue-grey">mdi-calendar</v-icon>
                        {{ ultima_cita.fecha }}</v-list-item-title
                    >

                    <v-card flat class="mt-4">
                        <v-row>
                            <v-col cols="12">
                                <span class="text-h6 font-weight-black mt-4 text-center">
                                    {{ ultima_cita.nombre_web }}

                                    {{ getCurrencyFormat(ultima_cita.importe) }}
                                </span>
                            </v-col>
                        </v-row>
                        <v-row class="mt-3" v-if="ultima_cita.notifica_web == 1">
                            <v-col cols="10" offset="1">
                                <v-alert outlined dense type="warning">Pendiente confirmar</v-alert>
                            </v-col>
                        </v-row>
                        <v-row v-else>
                            <v-col cols="8" offset="2">
                                <v-alert outlined type="success" dense>Cita confirmada</v-alert>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-list-item-content>
            </v-list-item>

            <v-card-actions class="pb-5 d-flex justify-center">
                <v-btn @click="openDialog()" class="red--text" large outlined rounded :loading="loading">Cancelar Cita</v-btn>
            </v-card-actions>
            <!-- </v-img> -->
        </v-card>
    </v-col>
</template>

<script>
import MyDialog from '@/Shared/MyDialog';
//import MyDialog from '../../Shared/MyDialog.vue';

export default {
    props: {
        ultima_cita: { type: Object },
        nom_ape: { type: String },
    },
    components: {
        MyDialog,
    },
    data() {
        return {
            loading: false,
            dialog: false,
        };
    },
    mounted() {},
    computed: {
        computedImgBono() {
            return '/assets/bono_numerado' + this.ultima_cita.bono.usadas + '.jpg';
        },
    },
    methods: {
        openDialog() {
            this.dialog = true;
        },
        destroyReg() {
            this.loading = true;
            this.$inertia.delete(route('book.destroy', this.ultima_cita.id), {
                onSuccess: (page) => {
                    this.$toast.success(page.props.flash.success);
                    this.loading = false;
                },
            });
            // axios
            //     .delete(route('book.destroy', this.ultima_cita.id))
            //     .then((res) => {
            //         this.$toast.success(res.data.message);
            //     })
            //     .catch((err) => {
            //         console.log(err.response);
            //     })
            //     .finally(() => {
            //         this.loading = false;
            //     });
        },
    },
};
</script>

<style></style>
