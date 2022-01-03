<template>
    <form-card
        :handleSubmit="confirmUserDeletion"
        :message="message"
        submitBtnText="Delete Account"
    >
        <template #title><h3>Delete Account</h3></template>

        <template #subtitle> Permanently delete your account. </template>

        <p>
            Once your account is deleted, all of its resources and data will be
            permanently deleted. Before deleting your account, please download
            any data or information that you wish to retain.
        </p>

        <!-- Delete Account Confirmation Modal -->
        <modal
            :show="confirmingUserDeletion"
            @close="confirmingUserDeletion = false"
        >
            <template #title> Borrar Cuenta </template>

            <template #content>
                <p>
                    Are you sure you want to delete your account? Once your
                    account is deleted, all of its resources and data will be
                    permanently deleted. Please enter your password to confirm
                    you would like to permanently delete your account.
                </p>

                <!-- Password Field -->
                <v-text-field
                    outlined
                    label="Password"
                    v-model="form.password"
                    autocomplete="current-password"
                    ref="password"
                    :type="showPassword ? 'text' : 'password'"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :error-messages="form.errors.password"
                    @keyup.enter="deleteUser"
                    @click:append="showPassword = !showPassword"
                ></v-text-field>
            </template>

            <template #footer>
                <v-spacer></v-spacer>

                <v-btn small @click.native="confirmingUserDeletion = false">
                    Cancelar
                </v-btn>

                <v-btn
                    small
                    color="error"
                    :loading="form.processing"
                    @click="deleteUser"
                >
                    Borrar Cuenta
                </v-btn>
            </template>
        </modal>
    </form-card>
</template>

<script>
import FormCard from "@/Components/FormCard";
import Modal from "@/Components/Modal";

export default {
    components: {
        FormCard,
        Modal,
    },

    data() {
        return {
            showPassword: false,
            confirmingUserDeletion: false,

            form: this.$inertia.form({
                password: "",
            }),
        };
    },
    computed: {
        message() {
            return {
                show: this.form.recentlySuccessful,
                text: "Ok",
                type: "success",
            };
        },
    },
    methods: {
        confirmUserDeletion() {
            this.confirmingUserDeletion = true;

            setTimeout(() => this.$refs.password.focus(), 250);
        },

        deleteUser() {
            this.form.delete(route("current-user.destroy"), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password.focus(),
                onFinish: () => this.form.reset(),
            });
        },

        closeModal() {
            this.confirmingUserDeletion = false;

            this.form.reset();
        },
    },
};
</script>
