<template>
    <v-card class="my-6 mr-4">
        <v-card-title class="overline">
            <h3>Permisos</h3>
        </v-card-title>
        <v-card-text class="py-0">
            <v-row>
                <v-col
                    v-for="permiso in permisos"
                    :key="permiso.name"
                    cols="12"
                    md="4"
                >
                    <v-switch
                        v-model="permisos_usr"
                        :label="permiso.nombre"
                        :value="permiso.name"
                        v-if="editable(permiso)"
                        color="primary"
                    >
                    </v-switch>

                    <v-chip v-else text class="mt-4" outlined color="success">
                        {{ permiso.nombre }}
                    </v-chip>
                </v-col>
                <v-col
                    v-if="permisos.length > 0"
                    cols="12"
                    offset-md="10"
                    md="2"
                >
                    <v-btn @click="setUserPermiso" small :loading="loading">
                        Guardar
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
<script>
export default {
    components: {},
    props: {
        user_id: {
            type: Number,
            required: true
        },
        from_role_permisos_heredados: {
            type: Array,
            required: true
        },
        from_role_permisos_usr: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            permisos: [],
            permisos_usr: [],
            permisos_heredados: [],
            loading: false
        };
    },
    watch: {
        from_role_permisos_usr: function() {
            this.permisos_usr = this.from_role_permisos_usr;
        },
        from_role_permisos_heredados: function() {
            this.permisos_heredados = this.from_role_permisos_heredados;
        }
    },
    mounted() {
        axios
            .get(route("user.permissions.show", this.user_id))
            .then(res => {
                this.permisos = res.data.permisos;
            })
            .catch(err => {
                console.log(err.response);
            });
    },
    methods: {
        editable(item) {
            //if (this.permisos_heredados.length == 0) return false;

            return this.permisos_heredados.findIndex(
                element => element === item.name
            ) >= 0
                ? false
                : true;
        },
        setUserPermiso() {
            this.loading = true;
            axios
                .put(route("user.permissions.update", this.user_id), {
                    permisos: this.permisos_usr
                })
                .then(res => {
                    this.permisos_usr = res.data.permisos_usr;
                    this.$toast.success("Permisos asignados!");
                })
                .catch(err => {
                    console.log(err.response);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
};
</script>
