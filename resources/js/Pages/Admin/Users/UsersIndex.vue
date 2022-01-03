<template>
    <div>
        <v-toolbar dense elevation="1">
            <h2>Usuarios</h2>
            <v-spacer></v-spacer>
            <menuope :id="item.id" :dialog.sync="dialog"></menuope>
        </v-toolbar>

        <v-container>
            <v-data-table
                :headers="headers"
                :items="paginator.data"
                :items-per-page="10"
                class="elevation-1"
            >
                <template v-slot:[`item.id`]="{ item }">
                    <v-avatar size="32px">
                        <img
                            class="img-fluid"
                            :src="item.profile_photo_url"
                            :alt="item.name"
                        />
                    </v-avatar>
                </template>
                <template v-slot:item.login_at="{ item }">
                    {{ getFechaHora(item.login_at) }}
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                    <v-icon small color="black" @click="editItem(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        :disabled="!hasRole('root')"
                        small
                        color="red darken-4"
                        @click="openDialog(item)"
                    >
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
            <div class="text-center">
                <v-pagination
                    v-model="current_page"
                    :length="last_page"
                    :total-visible="5"
                    circle
                ></v-pagination>
            </div>
        </v-container>
    </div>
</template>

<script>
import Menuope from "./Menuope";

export default {
    layout: null,
    props: {
        paginator: {
            type: Object,
            required: true,
        },
    },
    components: {
        Menuope,
    },
    data() {
        return {
            item: { id: 0 },
            current_page: 0,
            last_page: 0,
            dialog: false,
            editedIndex: -1,
            headers: [
                { text: "A", value: "id" },
                {
                    text: "Username",
                    align: "start",
                    value: "username",
                },
                {
                    text: "Nombre",
                    align: "start",
                    value: "name",
                },
                {
                    text: "Apellidos",
                    align: "start",
                    value: "lastname",
                },
                { text: "Login", value: "login_at" },
                { text: "Email", value: "email" },
                { text: "UM", value: "huella" },
                { text: "Acciones", value: "actions" },
            ],
        };
    },
    mounted() {
        this.current_page = this.paginator.current_page;
        this.last_page = this.paginator.last_page;
    },
    watch: {
        current_page(new_val) {
            if (new_val != this.paginator.current_page) {
                this.setMyHistoryUrl();
                this.$inertia.get("users", { page: new_val });
            }
        },
    },
    methods: {
        editItem(item) {
            this.setMyHistoryUrl();
            this.$inertia.get(route("users.edit", { user: item.id }));
        },
        openDialog(item) {
            this.dialog = true;
            this.item = item;
        },
    },
};
</script>
