<template>
    <v-app>
        <v-container class="mt-12">
            <v-layout row wrap align-center>
                <v-flex>
                    <v-card class="mx-auto" max-width="600">
                        <v-toolbar flat>
                            <v-toolbar-title>¿Olvidaste tu contraseña?</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn icon @click="home">
                                <v-icon>mdi-home-outline</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-container fluid id="container">
                            <v-card-text>
                                <v-form>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="form.email"
                                                label="Email"
                                                v-validate="'required|min:4'"
                                                :error-messages="errors.collect('email')"
                                                data-vv-name="email"
                                                data-vv-as="email"
                                                @keyup.enter="submit"
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
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="form.password_confirmation"
                                                label="Confirmar"
                                                v-validate="'required'"
                                                :error-messages="errors.collect('password_confirmation')"
                                                data-vv-name="password_confirmation"
                                                data-vv-as="password_confirmation"
                                                :type="showPassword ? 'text' : 'password'"
                                                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                                v-on:keyup.enter="submit"
                                                @click:append="showPassword = !showPassword"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="7"> </v-col>
                                        <v-col cols="4">
                                            <v-spacer></v-spacer>
                                            <v-btn small @click="submit" :loading="form.processing">
                                                Reset Password
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
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
    props: {
        email: String,
        token: String,
    },
    components: { HomeLayout },
    layout: HomeLayout,
    data() {
        return {
            showPassword: false,
            form: this.$inertia.form({
                token: this.token,
                email: this.email,
                password: '',
                password_confirmation: '',
            }),
        };
    },

    methods: {
        home() {
            this.$inertia.get(route('home'));
        },
        submit() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.form.post(this.route('password.update'), {
                        onFinish: () => {
                            const msg_valid = this.form.errors;
                            for (const prop in msg_valid) {
                                this.errors.add({
                                    field: prop,
                                    msg: `${msg_valid[prop]}`,
                                });
                            }

                            //  this.form.reset('password', 'password_confirmation');
                        },
                    });
                }
            });
        },
    },
};
</script>
