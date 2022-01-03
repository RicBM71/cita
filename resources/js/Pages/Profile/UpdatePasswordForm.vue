<template>
    <form-card :handleSubmit="updatePassword" :message="message">
        <template #title>
            <h3>Cambiar Password</h3>
        </template>

        <template #subtitle>
            La contraseña require de 8 caracteres, mayúscula, minúscula y símbolo ($%!@#?...
        </template>

        <!-- Current Password Field -->
        <v-text-field
            outlined
            label="Password Actual"
            v-model="form.current_password"
            autocomplete="current-password"
            :type="showCurrentPassword ? 'text' : 'password'"
            :append-icon="showCurrentPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :error-messages="form.errors.current_password"
            @click:append="showCurrentPassword = !showCurrentPassword"
        ></v-text-field>

        <!-- Password Field -->
        <v-text-field
            outlined
            label="Password"
            v-model="form.password"
            autocomplete="new-password"
            :type="showPassword ? 'text' : 'password'"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :error-messages="form.errors.password"
            @click:append="showPassword = !showPassword"
        ></v-text-field>

        <!-- Password Confirmation Field -->
        <v-text-field
            outlined
            label="Confirmar Password"
            v-model="form.password_confirmation"
            autocomplete="new-password"
            :type="showPasswordConfirmation ? 'text' : 'password'"
            :error-messages="form.errors.password_confirmation"
            :append-icon="showPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="showPasswordConfirmation = !showPasswordConfirmation"
        ></v-text-field>
    </form-card>
</template>

<script>
import FormCard from "../../Components/FormCard";

export default {
    components: {
        FormCard,
    },

    data() {
        return {
            showPassword: false,
            showPasswordConfirmation: false,
            showCurrentPassword: false,
            form: this.$inertia.form({
                current_password: "",
                password: "",
                password_confirmation: "",
            }),
        };
    },
    computed: {
        message() {
            return {
                show: this.form.recentlySuccessful,
                text: "Guardado",
                type: "success",
            };
        },
    },

    methods: {
        updatePassword() {
            this.form.put(route("user-password.update"), {
                errorBag: "updatePassword",
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
                onError: () => {
                    if (this.form.errors.password) {
                        this.form.reset("password", "password_confirmation");
                        this.$refs.password.focus();
                    }

                    if (this.form.errors.current_password) {
                        this.form.reset("current_password");
                        this.$refs.current_password.focus();
                    }
                },
            });
        },
    },
};
</script>
