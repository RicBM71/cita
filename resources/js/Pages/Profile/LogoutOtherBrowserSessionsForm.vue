<template>
    <form-card
        submitBtnText="Cerrar sesiones otros navegadores"
        :handleSubmit="confirmLogout"
        :message="message"
    >
        <template #title><h3>Sesiones Navegador</h3></template>

        <template #subtitle>
            Desactiva y cierra la sessión de otros navegadores y dispositivos
        </template>

        <p>
            Si es necesario, podrías cerrar el resto de sesiones abiertas en otros
            navegadores en otros dispositivos. Se listan la sesiones más recientes, sin
            embargo podrían no mostrarse todas ellas.
            Si crees que tu cuenta se ha visto comprometida, cierra todas las sesiones
            y restaura tu password.
        </p>

        <!-- Other Browser Sessions -->
        <v-list v-if="sessions.length > 0">
            <v-list-item v-for="(session, i) in sessions" :key="i">
                <v-list-item-icon>
                    <v-icon v-if="session.agent.is_desktop">
                        mdi-desktop-mac
                    </v-icon>
                    <v-icon v-else> mdi-cellphone-iphone </v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>
                        {{ session.agent.platform }} -
                        {{ session.agent.browser }}
                    </v-list-item-title>

                    <v-list-item-subtitle>
                        {{ session.ip_address }},

                        <span
                            class="success--text font-weight-bold"
                            v-if="session.is_current_device"
                        >
                            Este dispositivo
                        </span>

                        <template v-else>
                            Última activa {{ session.last_active }}
                        </template>
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>

        <!-- Logout Other Devices Confirmation Modal -->
        <modal :show="confirmingLogout" @close="confirmingLogout = false">
            <template #title> Cerrar sesiones </template>

            <template #content>
                <p>
                    Por favor, introduce tu password actual para confirmar
                    el cierre de todas las sessiones.
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
                    @keyup.enter="logoutOtherBrowserSessions"
                    @click:append="showPassword = !showPassword"
                ></v-text-field>
            </template>

            <template #footer>
                <v-spacer></v-spacer>

                <v-btn small @click.native="confirmingLogout = false">
                    Cancelar
                </v-btn>

                <v-btn
                    small
                    @click.native="logoutOtherBrowserSessions"
                    :loading="form.processing"
                >
                    Logout
                </v-btn>
            </template>
        </modal>
    </form-card>
</template>


<script>
import FormCard from "@/Components/FormCard";
import Modal from "@/Components/Modal";

export default {
    props: ["sessions"],

    components: {
        FormCard,
        Modal,
    },

    data() {
        return {
            confirmingLogout: false,
            showPassword: false,

            form: this.$inertia.form({
                password: "",
            }),
        };
    },
    computed: {
        message() {
            return {
                show: this.form.recentlySuccessful,
                text: "Ok.",
                type: "success",
            };
        },
    },

    methods: {
        confirmLogout() {
            this.confirmingLogout = true;

            setTimeout(() => this.$refs.password.focus(), 250);
        },

        logoutOtherBrowserSessions() {
            this.form.delete(route("other-browser-sessions.destroy"), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password.focus(),
                onFinish: () => this.form.reset(),
            });
        },

        closeModal() {
            this.confirmingLogout = false;

            this.form.reset();
        },
    },
};
</script>
