<template>
    <div>
        <v-toolbar dense elevation="1">
            <h2>{{ usuario.username }}</h2>
            <v-spacer></v-spacer>
            <menuope :id="usuario.id"></menuope>
        </v-toolbar>

        <v-container>
            <v-card>
                <v-card-title color="indigo"> </v-card-title>
                <v-tabs fixed-tabs>
                    <v-tab> Datos generales </v-tab>
                    <v-tab> Empresas </v-tab>
                    <v-tab-item>
                        <v-row>
                            <v-col cols="12" md="7">
                                <update-user-information :user="usuario" />
                            </v-col>

                            <v-col cols="12" md="5">
                                <user-roles :user_id="usuario.id"></user-roles>
                            </v-col>
                        </v-row>
                    </v-tab-item>
                </v-tabs>
            </v-card>
        </v-container>
    </div>
</template>
<script>
import Menuope from "./Menuope";
import UpdateUserInformation from "./UpdateUserInformation";
import UserRoles from "./UserRoles";

export default {
    components: {
        Menuope,
        UpdateUserInformation,
        UserRoles,
    },
    data() {
        return {};
    },
    props: {
        usuario: {
            type: Object,
            required: true,
        },
    },
    mounted() {},
    methods: {
        openDialog() {},
    },
};
</script>
