<template>
    <div>
        <v-toolbar dense elevation="1">
            <h2>Crear Usuario</h2>
            <v-spacer></v-spacer>
            <menuope></menuope>
        </v-toolbar>
        <v-container>
            <v-card>
                <v-card-title color="indigo"> </v-card-title>
                <v-form @submit.prevent="store">
                    <v-card-text class="py-0">
                        <v-row>
                            <v-col cols="12" md="2">
                                <v-text-field
                                    label="Nombre"
                                    dense
                                    v-model="form.name"
                                    :error-messages="form.errors.name"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-text-field
                                    label="Usuario"
                                    dense
                                    v-model="form.username"
                                    :error-messages="form.errors.username"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    label="Email"
                                    dense
                                    v-model="form.email"
                                    :error-messages="form.errors.email"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="2">
                                <v-btn
                                    block
                                    small
                                    type="submit"
                                    :loading="loading"
                                >
                                    Guardar
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-form>
            </v-card>
        </v-container>
    </div>
</template>
<script>
import Menuope from "./Menuope";
export default {
    components: {
        Menuope
    },
    data() {
        return {
            form: this.$inertia.form({
                name: null,
                username: null,
                email: null
            }),
            loading: false
        };
    },
    mounted() {},
    methods: {
        store() {
            this.loading = true;
            this.form.post(route("users.store"), {
                preserveScroll: true,
                onFinish: () => {
                    this.loading = false;
                }
            });
        }
    }
};
</script>
