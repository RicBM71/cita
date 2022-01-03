<template>
    <div>
        <my-dialog :dialog.sync="dialog" @destroyReg="destroyReg"></my-dialog>
        <v-toolbar dense elevation="1">
            <h2>Roles</h2>
            <v-spacer></v-spacer>
            <menuope></menuope>
        </v-toolbar>

        <v-container>
            <v-data-table
                :headers="headers"
                :items="items"
                :items-per-page="10"
                class="elevation-1"
            >
                <template v-slot:item.permissions="{ item }">
                    {{ getPermisos(item.permissions) }}
                </template>
                <template v-slot:item.updated_at="{ item }">
                    {{ getFechaHora(item.updated_at) }}
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
        </v-container>
    </div>
</template>

<script>
import Menuope from "./Menuope";
import MyDialog from "@/Shared/MyDialog";

export default {
    layout: null,
    props: {
        items: {
            type: Array,
            required: true,
        },
    },
    components: {
        Menuope,
        MyDialog,
    },
    data() {
        return {
            item: {},
            roles: [],
            dialog: false,
            editedIndex: -1,
            headers: [
                {
                    text: "Role",
                    align: "start",
                    value: "name",
                },
                { text: "Permisos", value: "permissions", sortable: false },
                { text: "UM", value: "updated_at" },
                { text: "Acciones", value: "actions" },
            ],
        };
    },
    beforeMount() {
        this.roles = this.items;
    },
    mounted() {},
    methods: {
        getPermisos(item) {
            return _.replace(_.toString(_.map(item, "nombre")), ",", ", ");
        },
        editItem(item) {
            this.setMyHistoryUrl();
            this.$inertia.get(route("roles.edit", { role: item.id }));
        },
        openDialog(item) {
            this.dialog = true;
            this.item = item;
        },
        destroyReg() {
            this.editedIndex = this.paginator.data.indexOf(this.item);
            axios
                .delete(route("roles.destroy", { user: this.item.id }))
                .then((res) => {
                    this.paginator.data.splice(this.editedIndex, 1);
                    this.$toast.success(res.data.message);
                })
                .catch((err) => {
                    this.$toast.error(err.response.data.message);
                })
                .finally(() => {});
        },
    },
};
</script>
