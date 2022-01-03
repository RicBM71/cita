<template>
    <v-container>
        <v-btn block small :loading="form.processing" @click="startConfirmingPassword">
            <slot />
        </v-btn>

        <modal :show="confirmingPassword" @close="confirmingPassword = false">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                <v-row>
                    <v-col cols="12">
                        {{ content }}
                    </v-col>
                </v-row>
                <v-row align-content="center">
                    <v-col cols="12" lg="10">
                        <!-- Password Field -->
                        <v-text-field
                            outlined
                            ref="password"
                            label="Password"
                            v-model="form.password"
                            autocomplete="current-password"
                            :type="showPassword ? 'text' : 'password'"
                            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            :error-messages="form.error"
                            @click:append="showPassword = !showPassword"
                            @keyup.enter.native="confirmPassword"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </template>

            <template #footer>
                <v-spacer></v-spacer>

                <v-btn small @click.native="confirmingPassword = false">
                    Cancelar
                </v-btn>

                <v-btn small @click.native="confirmPassword" :disabled="form.processing">
                    {{ button }}
                </v-btn>
            </template>
        </modal>
    </v-container>
</template>

<script>
import Modal from './Modal';

export default {
    props: {
        title: {
            default: 'Confirmar Password',
        },
        content: {
            default: 'Por tu seguridad, por favor confirma tu password para continuar.',
        },
        button: {
            default: 'Confirmar',
        },
    },

    components: {
        Modal,
    },

    data() {
        return {
            confirmingPassword: false,

            form: this.$inertia.form(
                {
                    password: '',
                    error: '',
                },
                {
                    bag: 'confirmPassword',
                }
            ),

            showPassword: false,
        };
    },

    methods: {
        startConfirmingPassword() {
            this.form.error = '';

            axios.get(route('password.confirmation')).then((response) => {
                if (response.data.confirmed) {
                    this.$emit('confirmed');
                } else {
                    this.confirmingPassword = true;
                    this.form.password = '';

                    setTimeout(() => {
                        this.$refs.password.focus();
                    }, 250);
                }
            });
        },

        confirmPassword() {
            this.form.processing = true;

            axios
                .post(route('password.confirm'), {
                    password: this.form.password,
                })
                .then((response) => {
                    this.confirmingPassword = false;
                    this.form.password = '';
                    this.form.error = '';
                    this.form.processing = false;

                    this.$nextTick(() => this.$emit('confirmed'));
                })
                .catch((error) => {
                    this.form.processing = false;
                    this.form.error = error.response.data.errors.password[0];
                });
        },
    },
};
</script>
