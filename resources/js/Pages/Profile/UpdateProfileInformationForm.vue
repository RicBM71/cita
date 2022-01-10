<template>
    <v-card>
        <v-form>
            <v-card-title>
                <h3>Perfil de usuario</h3>
            </v-card-title>

            <v-card-subtitle>
                Actualiza tu password, email.
            </v-card-subtitle>

            <!-- Profile Photo -->
            <v-card-text class="py-0">
                <v-divider class="my-4"></v-divider>
                <template v-if="$page.props.jetstream.managesProfilePhotos">
                    <!-- Profile Photo File Input -->
                    <input type="file" class="d-none" ref="photo" @change="updatePhotoPreview" />

                    <!-- Current Profile Photo -->
                    <div v-show="!photoPreview">
                        <v-avatar size="84">
                            <img :src="user.profile_photo_url" alt="Current Profile Photo" />
                        </v-avatar>
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div v-show="photoPreview">
                        <v-avatar size="84">
                            <img :src="photoPreview" alt="New Profile Photo" />
                        </v-avatar>
                    </div>

                    <v-btn class="mt-5 mr-2" x-small @click.native.prevent="selectNewPhoto">
                        Seleccionar Avatar
                    </v-btn>

                    <v-btn v-if="user.profile_photo_path" x-small class="mt-5" color="warning" @click.native.prevent="deletePhoto">
                        Borrar Avatar
                    </v-btn>
                </template>

                <!-- First Name -->
                <v-text-field outlined autocomplete="given-name" label="Nombre" v-model="form.name" class="mt-8" readonly></v-text-field>

                <!-- Last Name -->
                <v-text-field outlined autocomplete="family-name" label="Apellidos" v-model="form.lastname" readonly></v-text-field>

                <!-- Email -->
                <v-text-field
                    readonly
                    outlined
                    autocomplete="email"
                    label="Email"
                    v-model="form.email"
                    :error-messages="form.errors.email"
                ></v-text-field>
                <v-card-actions>
                    <v-btn small block @click="updateProfileInformation" :loading="loading">Guardar</v-btn>
                </v-card-actions>
            </v-card-text>
        </v-form>
    </v-card>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            form: this.$inertia.form({
                _method: 'PUT',
                name: this.user.name,
                lastname: this.user.lastname,
                email: this.user.email,
                photo: null,
            }),

            photoPreview: null,
            loading: false,
        };
    },

    computed: {
        message() {
            return {
                show: this.form.recentlySuccessful,
                text: 'Guardado',
                type: 'success',
            };
        },
    },

    methods: {
        updateProfileInformation() {
            this.loading = true;
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0];
            }

            this.form.post(route('user-profile-information.update'), {
                preserveScroll: true,
                onSuccess: (page) => {
                    this.$toast.success(page.props.flash.success);
                },
                onFinish: () => {
                    this.loading = false;
                },
            });
        },
        selectNewPhoto() {
            this.$refs.photo.click();
        },

        updatePhotoPreview() {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };

            reader.readAsDataURL(this.$refs.photo.files[0]);
        },

        deletePhoto() {
            this.$inertia.delete(route('current-user-photo.destroy'), {
                preserveScroll: true,
                onSuccess: () => (this.photoPreview = null),
            });
        },
    },
};
</script>
