<template>
    <div>
        <v-card class="mr-4 mt-2">
            <v-card-title class="overline">
                <h3>Roles</h3>
            </v-card-title>
            <v-card-text class="py-0">
                <v-row>
                    <v-col v-for="role in roles" :key="role" cols="12" md="3">
                        <v-switch
                            v-model="roles_usr"
                            :label="role"
                            :value="role"
                            color="primary"
                        >
                        </v-switch>
                    </v-col>

                    <v-col
                        v-if="roles.length > 0"
                        cols="12"
                        offset-md="10"
                        md="2"
                    >
                        <v-btn @click="setUserRole" small :loading="loading">
                            Guardar
                        </v-btn>
                    </v-col>
                </v-row>
                <!-- <v-row v-if="permisos_heredados.length > 0">
                <v-col v-for="heredado in permisos_heredados" :key="heredado.name" cols="12" md="3">
                    <v-chip  class="caption" outlined color="blue">{{heredado.nombre}}</v-chip>
                </v-col>
            </v-row> -->
            </v-card-text>
        </v-card>
        <user-permisos
            :user_id="user_id"
            :from_role_permisos_heredados="permisos_heredados"
            :from_role_permisos_usr="permisos_usr"
        ></user-permisos>
    </div>
</template>
<script>
import UserPermisos from "./UserPermisos";
export default {
    components: { UserPermisos },
    data() {
        return {
            roles: [],
            roles_usr: [],
            permisos_heredados: [],
            permisos_usr: [],
            loading: false
        };
    },
    props: {
        user_id: {
            type: Number,
            required: true
        }
    },
    mounted() {
        axios
            .get(route("user.roles.show", this.user_id))
            .then(res => {
                this.roles = res.data.roles;
                this.roles_usr = res.data.roles_usr;
                this.permisos_heredados = res.data.permisos_heredados;
                this.permisos_usr = res.data.permisos_usr;
            })
            .catch(err => {
                console.log(err.response);
            });
    },
    methods: {
        setUserRole() {
            this.loading = true;
            // this.$inertia.put(
            //     route("user.roles.update",this.user_id),
            //     {roles: this.roles_usr},
            //     {
            //         onFinish: () => (this.loading = false),
            //     }
            // );
            axios
                .put(route("user.roles.update", this.user_id), {
                    roles: this.roles_usr
                })
                .then(res => {
                    this.permisos_heredados = res.data.permisos_heredados;
                    this.permisos_usr = res.data.permisos_usr;
                    this.$toast.success("Roles y permisos asignados!");
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
