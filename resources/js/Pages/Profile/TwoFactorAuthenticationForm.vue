<template>
  <form-card
    :handleSubmit="enableTwoFactorAuthentication"
    :message="message"
  >
    <template #title>
      <h3>Autenticación doble factor</h3>
    </template>

    <template #subtitle>
      Aplica una capa adicional de seguridad a tu cuenta de usuario.
    </template>

    <h3 v-if="twoFactorEnabled">
      Tienes <span class="green--text">ACTIVADA</span> la Autenticación de doble factor.
    </h3>

    <h3 v-else>
      Tienes <span class="red--text">DESACTIVADA</span> la Autenticación de doble factor.
    </h3>

    <p>
        Cuando actives la Autenticación de doble factor, necesitarás de un código aleatorio que podrás obtener
        a través de tu teléfono y la aplicación Google Authenticator.
    </p>

    <template v-if="twoFactorEnabled">
      <div v-if="qrCode">
        <div class="mt-4 max-w-xl text-sm text-gray-600">
          <p class="font-semibold">
              La autenticación de dos factores está ACTIVADA. Scanea el siguiente código QR para activarlo en tu App Authenticator.
          </p>
        </div>

        <div class="mt-4 dark:p-4 dark:w-56 dark:bg-white" v-html="qrCode">
        </div>
      </div>

      <div v-if="recoveryCodes.length > 0">
        <div class="mt-4 max-w-xl text-sm text-gray-600">
          <p class="font-semibold">
              Guarda estos códigos de recuperación en una App de passwords. Los podrás utilizar para acceder a tu cuenta en
              caso de pérdida de tu dispositivo móvil.
          </p>
        </div>

        <v-list>
          <v-list-item-content v-for="code in recoveryCodes" :key="code">
            <v-list-item-subtitle>
              {{ code }}
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list>
      </div>
    </template>

    <template #footer>
      <confirms-password
        v-if="!twoFactorEnabled"
        @confirmed="enableTwoFactorAuthentication"
      >
        Activar
      </confirms-password>

      <template v-else>
        <confirms-password
          v-if="recoveryCodes.length > 0"
          @confirmed="regenerateRecoveryCodes"
        >
          Regenerar códigos
        </confirms-password>

        <confirms-password
          v-if="recoveryCodes.length == 0"
          @confirmed="showRecoveryCodes"
        >
          Mostrar códigos
        </confirms-password>

        <confirms-password
          @confirmed="disableTwoFactorAuthentication"
        >
          Desactivar
        </confirms-password>
      </template>
    </template>
  </form-card>
</template>

<script>
import FormCard from '../../Components/FormCard'
import ConfirmsPassword from '@/Components/ConfirmsPassword'

export default {
  components: {
    ConfirmsPassword,
    FormCard
  },

        data() {
            return {
                enabling: false,
                disabling: false,

                qrCode: null,
                recoveryCodes: [],
            }
        },

        methods: {
            enableTwoFactorAuthentication() {
                this.enabling = true

                this.$inertia.post('/user/two-factor-authentication', {}, {
                    preserveScroll: true,
                    onSuccess: () => Promise.all([
                        this.showQrCode(),
                        this.showRecoveryCodes(),
                    ]),
                    onFinish: () => (this.enabling = false),
                })
            },

            showQrCode() {
                return axios.get('/user/two-factor-qr-code')
                        .then(response => {
                            this.qrCode = response.data.svg
                        })
            },

            showRecoveryCodes() {
                return axios.get('/user/two-factor-recovery-codes')
                        .then(response => {
                            this.recoveryCodes = response.data
                        })
            },

            regenerateRecoveryCodes() {
                axios.post('/user/two-factor-recovery-codes')
                        .then(response => {
                            this.showRecoveryCodes()
                        })
            },

            disableTwoFactorAuthentication() {
                this.disabling = true

                this.$inertia.delete('/user/two-factor-authentication', {
                    preserveScroll: true,
                    onSuccess: () => (this.disabling = false),
                })
            },
        },

        computed: {
            message () {
                return {
                    show: false,
                    text: 'Guardado',
                    type: 'success'
                }
            },
            twoFactorEnabled() {
                return ! this.enabling && this.$page.props.user.two_factor_enabled
            }
        }
    }
</script>
