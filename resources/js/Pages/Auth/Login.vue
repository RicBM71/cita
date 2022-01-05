<template>
    <v-app>
        <v-container class="mt-12">
            <v-layout row wrap align-center>
                <v-flex>
                    <v-card class="mx-auto" max-width="600" elevation="1" shaped>
                        <v-toolbar flat>
                            <v-toolbar-title
                                ><v-avatar tile><v-img src="assets/logo_ico.png"></v-img></v-avatar
                                ><span class="primary--text">Sanaval</span></v-toolbar-title
                            >
                            <v-spacer></v-spacer>
                            <v-btn icon @click="home">
                                <v-icon class="primary--text">mdi-home-outline</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-container fluid>
                            <v-card-text id="container">
                                <v-form>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="form.email"
                                                label="email"
                                                v-validate="'required|email'"
                                                :error-messages="errors.collect('email')"
                                                data-vv-name="email"
                                                data-vv-as="email"
                                                v-on:keyup.enter="submit"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="form.password"
                                                label="Password"
                                                v-validate="'required'"
                                                :error-messages="errors.collect('password')"
                                                data-vv-name="password"
                                                data-vv-as="password"
                                                :type="showPassword ? 'text' : 'password'"
                                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                                v-on:keyup.enter="submit"
                                                @click:append="showPassword = !showPassword"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="8" sm="12" lg="8">
                                            <inertia-link v-if="canResetPassword" :href="route('password.request')">
                                                Olvidaste tu contraseña?
                                            </inertia-link>
                                        </v-col>
                                        <v-col cols="12" sm="12" lg="2">
                                            <v-btn block outlined color="primary" rounded @click="submit" :loading="form.processing">
                                                <v-icon>mdi-login</v-icon>
                                                Entrar
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                                <div class="mt-5 text-caption text-center">
                                    * Servicio sólo disponible para pacientes previamente autorizados.
                                </div>
                            </v-card-text>
                        </v-container>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
import HomeLayout from '@/Components/Layout/HomeLayout';
export default {
    components: { HomeLayout },
    layout: HomeLayout,
    props: {
        canResetPassword: Boolean,
        status: String,
    },
    data() {
        return {
            loading: false,
            showPassword: false,
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false,
            }),
        };
    },

    methods: {
        home() {
            //this.$inertia.get(route('home'));
            window.location.href = 'https://sanaval.com';
        },
        submit() {
            if (this.loading === false) {
                this.loading = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.form
                            .transform((data) => ({
                                ...data,
                                remember: this.form.remember ? 'on' : '',
                            }))
                            .post(this.route('login'), {
                                onFinish: () => {
                                    const msg_valid = this.form.errors;
                                    for (const prop in msg_valid) {
                                        this.errors.add({
                                            field: prop,
                                            msg: `${msg_valid[prop]}`,
                                        });
                                    }
                                    //this.form.reset('password');
                                    this.loading = false;
                                },
                            });
                    } else {
                        this.loading = false;
                    }
                });
            }
        },
    },
};
</script>
