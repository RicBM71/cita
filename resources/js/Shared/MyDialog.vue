<template>
    <v-dialog v-model="dialog" persistent max-width="290">
        <v-card>
            <v-card-title class="justify-center">Atención</v-card-title>
            <v-card-text class="justify-center">{{ message }}</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDialog()">Cancelar</v-btn>
                <v-btn color="blue darken-1" text @click="deleteItem">Aceptar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: {
        dialog: {
            type: Boolean,
            default: false,
        },
        message: {
            type: String,
            default: '¿Estás seguro de Cancelar la cita?',
        },
    },
    components: {},
    data() {
        return {};
    },
    mounted() {},
    methods: {
        closeDialog() {
            this.$emit('update:dialog', false);
        },
        deleteItem() {
            this.$emit('destroyReg');
            this.$emit('update:dialog', false);
        },
    },
};
</script>
