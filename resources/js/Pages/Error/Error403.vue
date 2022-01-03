<template>
    <div>

            <h2>Error {{statusCode }}!!</h2>

        <v-container>

            <v-card class="d-flex justify-center mb-6" flat tile>
                <v-alert outlined type="error">
                    {{ message }}, pulse
                    <inertia-link :href="route('dashboard')">
                        aquí
                    </inertia-link>
                    para volver a inicio
                </v-alert>

            </v-card>
        </v-container>
    </div>
</template>

<script>
export default {
    props: ["statusCode", "message"],
    components: {
    },
    data() {
        return {};
    },
    mounted() {

    },
};
</script>
