<template>
    <v-app>
        <v-container class="mt-12">
            <v-layout row wrap align-center>
                <v-flex>
                    <v-card class="mx-auto" max-width="600">
                        <v-toolbar color="primary" dark>
                            <v-toolbar-title
                                >Confirmar password</v-toolbar-title
                            >
                            <v-spacer></v-spacer>
                            <v-btn icon @click="home">
                                <v-icon>mdi-home-outline</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-container fluid id="container">
                            <v-card-text>
                                <p>
                                    Este es un area segura de la aplicación. Por
                                    favor, confirma tu password para continuar.
                                </p>

                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-show="show"
                                            v-model="show"
                                        ></v-text-field>
                                        <v-text-field
                                            v-model="form.password"
                                            label="Password"
                                            type="password"
                                            :error-messages="
                                                form.errors.password
                                            "
                                            @keyup.enter="submit"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="9"></v-col>
                                    <v-col cols="2">
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            small
                                            @click="submit"
                                            :loading="form.processing"
                                        >
                                            Confirmar
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-container>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>
<script>
import HomeLayout from '@/Components/Layout/HomeLayout'
export default {
    components:{HomeLayout},
    layout: HomeLayout,
    data() {
        return {
            show: false,
            form: this.$inertia.form({
                password: "",
            }),
        };
    },
    methods: {
        home() {
            this.$inertia.get(route("dashboard"));
        },
        submit() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.form.post(this.route("password.confirm"), {
                        onFinish: () => {
                            const msg_valid = this.form.errors;
                            for (const prop in msg_valid) {
                                this.errors.add({
                                    field: prop,
                                    msg: `${msg_valid[prop]}`,
                                });
                            }
                        },
                    });
                }
            });
        },
    },
};
</script>
