<template>
    <v-snackbar v-model="computedSnackbar" top centered :color="computedColor">
        {{ data.message }}
        <template v-slot:action="{ attrs }">
            <v-btn :color="computedBtn" text v-bind="attrs" @click="closeDialog">
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
export default {
    props: {
        snackbar: {
            type: Boolean,
            default: false,
        },
        data:{
            type: Object,
            required: true
        }
    },
    data() {
        return {

        };
    },
    computed: {
        computedColor(){
            return this.data.status == 200 ? 'green' :  'blue-grey darken-4';
        },
        computedBtn(){
            return this.data.status == 200 ? 'white' :  'red';
        },
        computedSnackbar: {
            // getter
            get: function () {
                return this.snackbar;
            },
            // setter
            set: function (newValue) {
                this.$emit("update:snackbar", newValue);
            },
        },
    },
    methods: {
        closeDialog() {
            this.$emit("update:snackbar", false);
        },
    },
};
</script>
