<template>
    <div class="d-flex">
        <my-dialog :dialog.sync="dialog" @destroyReg="destroyReg"></my-dialog>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    class="mr-1"
                    v-show="computedAdd"
                    v-on="on"
                    x-small
                    @click="goCreate"
                >
                    Nuevo
                </v-btn>
            </template>
            <span>Crear un nuevo registro</span>
        </v-tooltip>
        <v-tooltip bottom v-if="id > 0">
            <template v-slot:activator="{ on }">
                <v-btn
                    :disabled="!hasPermiso('root')"
                    class="mr-1"
                    v-on="on"
                    x-small
                    @click="openDialog"
                >
                    Borrar
                </v-btn>
            </template>
            <span>Borrar Registro actual</span>
        </v-tooltip>
        <v-tooltip bottom v-if="id > 0">
            <template v-slot:activator="{ on }">
                <v-btn class="mr-1" v-on="on" x-small @click="goIndex">
                    Lista
                </v-btn>
            </template>
            <span>Volver a la lista de resultados</span>
        </v-tooltip>

        <v-btn class="mr-1" x-small @click="goBack"> Volver </v-btn>
    </div>
</template>
<script>
import MyDialog from "@/Shared/MyDialog";
export default {
    props: {
        id: {
            type: Number,
            default: 0,
        },
    },
    components: {
        MyDialog,
    },
    data() {
        return {
            dialog: false,
        };
    },
    computed: {
        computedAdd() {
            return true;
        },
    },
    methods: {
        goCreate() {},
        goIndex() {
            this.$inertia.get(route("users.index"));
        },
        openDialog() {
            this.dialog = true;
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
        goBack() {
            this.goBackUrl();
        },
    },
};
</script>
