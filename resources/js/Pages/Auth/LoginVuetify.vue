<template>
    <v-app>
        <v-container>
            <v-row>
                <v-col cols="12" md="4"></v-col>
                <v-col cols="12" md="4">
                    <v-card class="mx-auto">
                        <v-card-title>
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Credenciales</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <inertia-link
                                    :href="route('home')"
                                    class="text-sm text-gray-700 underline"
                                >
                                    <v-icon>mdi-home-outline</v-icon>
                                </inertia-link>
                            </v-toolbar>
                        </v-card-title>
                        <v-card-text>
                            <v-form>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="form.email"
                                            label="Usuario"
                                            v-validate="'required|min:4'"
                                            :error-messages="
                                                errors.collect('email')
                                            "
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
                                            :error-messages="
                                                errors.collect('password')
                                            "
                                            data-vv-name="password"
                                            data-vv-as="password"
                                            type="password"
                                            v-on:keyup.enter="submit"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="9">
                                        <inertia-link
                                            v-if="canResetPassword"
                                            :href="route('password.request')"
                                        >
                                            Forgot your password?
                                        </inertia-link>
                                    </v-col>
                                    <v-col cols="2">
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            small
                                            @click="submit"
                                            :disabled="form.processing"
                                        >
                                            Login
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>
export default {
    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            loading: false,
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false,
            }),
        };
    },

    methods: {
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
