<template>
  <div style="width: 100%;">
    <v-btn
      block
      small
      @click="startConfirmingPassword"
    >
      <slot />
    </v-btn>

    <modal
      :show="confirmingPassword"
      @close="confirmingPassword = false"
    >
      <template #title>
        {{ title }}
      </template>

      <template #content>
        <p>
          {{ content }}
        </p>

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
      </template>

      <template #footer>
        <v-spacer></v-spacer>

        <v-btn
            small
            text
            @click.native="confirmingPassword = false"
        >
          Nevermind
        </v-btn>

        <v-btn
          small
          @click.native="confirmPassword"
          :disabled="form.processing"
        >
          {{ button }}
        </v-btn>
      </template>
    </modal>
  </div>
</template>

<script>
alert("pasa");
import Modal from './Modal'

    export default {
        props: {
            title: {
                default: 'Confirm Password',
            },
            content: {
                default: 'For your security, please confirm your password to continue.',
            },
            button: {
                default: 'Confirm',
            }
        },
        components: {
            Modal
        },
        data() {
            return {
                confirmingPassword: false,
                form: {
                    password: '',
                    error: '',
                },
            }
        },

        methods: {
            startConfirmingPassword() {
                axios.get(route('password.confirmation')).then(response => {
                    if (response.data.confirmed) {
                        this.$emit('confirmed');
                    } else {
                        this.confirmingPassword = true;

                        setTimeout(() => this.$refs.password.focus(), 250)
                    }
                })
            },

            confirmPassword() {
                this.form.processing = true;

                axios.post(route('password.confirm'), {
                    password: this.form.password,
                }).then(() => {
                    this.form.processing = false;
                    this.closeModal()
                    this.$nextTick(() => this.$emit('confirmed'));
                }).catch(error => {
                    this.form.processing = false;
                    this.form.error = error.response.data.errors.password[0];
                    this.$refs.password.focus()
                });
            },

            closeModal() {
                this.confirmingPassword = false
                this.form.password = '';
                this.form.error = '';
            },
        }
    }
</script>
