<template>
    <v-card class="ma-2">
        <v-card-title class="overline">
            <h3>Perfil</h3>
        </v-card-title>

        <v-form @submit.prevent="update">
            <v-card-text class="py-0">
                <v-row>
                    <v-col cols="12">
                        <template
                            v-if="$page.props.jetstream.managesProfilePhotos"
                        >
                            <!-- Profile Photo File Input -->
                            <input
                                type="file"
                                class="d-none"
                                ref="photo"
                                @change="updatePhotoPreview"
                            />

                            <!-- Current Profile Photo -->
                            <div v-show="!photoPreview">
                                <v-avatar size="84">
                                    <img
                                        :src="user.profile_photo_url"
                                        alt="Current Profile Photo"
                                    />
                                </v-avatar>
                            </div>

                            <!-- New Profile Photo Preview -->
                            <div v-show="photoPreview">
                                <v-avatar size="84">
                                    <img
                                        :src="photoPreview"
                                        alt="New Profile Photo"
                                    />
                                </v-avatar>
                            </div>

                            <v-btn
                                class="mt-5 mr-2"
                                x-small
                                @click.native.prevent="selectNewPhoto"
                            >
                                Selecciona Avatar
                            </v-btn>

                            <v-btn
                                v-if="user.profile_photo_path"
                                x-small
                                :loading="loading_photo"
                                class="mt-5"
                                color="warning"
                                @click.native.prevent="deletePhoto"
                            >
                                Borrar Avatar
                            </v-btn>
                        </template>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="2">
                        <v-text-field
                            label="Nombre"
                            dense
                            v-model="form.name"
                            :error-messages="form.errors.name"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            label="Apellidos"
                            dense
                            v-model="form.lastname"
                            :error-messages="form.errors.lastname"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-text-field
                            label="Usuario"
                            dense
                            v-model="form.username"
                            :error-messages="form.errors.username"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            label="Email"
                            dense
                            v-model="form.email"
                            :error-messages="form.errors.email"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="2">
                        <v-switch
                            class="mt-2"
                            dense
                            v-model="form.blocked"
                            label="Bloqueado"
                        ></v-switch>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            readonly
                            dense
                            :label="user.username_umod"
                            v-model="computedUpdatedAt"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            readonly
                            dense
                            label="Creado"
                            v-model="computedCreatedAt"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-btn block small type="submit" :loading="loading">
                            Guardar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-form>
    </v-card>
</template>

<script>
export default {
    props: ["user"],
    data() {
        return {
            form: this.$inertia.form({
                name: this.user.name,
                username: this.user.username,
                lastname: this.user.lastname,
                email: this.user.email,
                photo: null
            }),

            photoPreview: null,
            loading: false,
            loading_photo: false
        };
    },
    mounted() {},
    watch: {
        msgSuccess(val) {
            if (val != null) {
                this.$toast.success(`${val}`);
                this.$page.props.flash.success = null;
            }
        }
    },
    computed: {
        // computedHuella() {
        //     return (
        //         this.user.username_umod +
        //         " " +
        //         this.$moment(this.user.updated_at).format("D/MM/YYYY H:mm:ss")
        //     );
        // },
        computedUpdatedAt() {
            return this.getFechaHora(this.user.updated_at);
        },
        computedCreatedAt() {
            return this.getFechaHora(this.user.created_at);
        }
    },

    methods: {
        update() {
            this.loading = true;
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0];
            }

            this.form.put(route("users.update", this.user.id), {
                preserveScroll: true,
                onFinish: () => {
                    this.loading = false;
                }
            });
        },

        selectNewPhoto() {
            this.$refs.photo.click();
        },

        updatePhotoPreview() {
            const reader = new FileReader();

            reader.onload = e => {
                this.photoPreview = e.target.result;
            };

            reader.readAsDataURL(this.$refs.photo.files[0]);
        },

        deletePhoto() {
            this.loading_photo = true;
            this.$inertia.delete(route("photo.destroy", { user: this.user }), {
                preserveScroll: true,
                onSuccess: () => (this.photoPreview = null),
                onFinish: () => (this.loading_photo = false)
            });
        },
        roles() {
            this.setMyHistoryUrl();
            this.$inertia.get(route("roles.index"));
        }
    }
};
</script>
