<template>
    <error-layout>
        <template #header>
            <v-app-bar color="blue-grey darken-1" dark>
                <v-toolbar-title
                    ><h2>
                        {{ appName }} - Error {{ statusCode }}!!
                    </h2></v-toolbar-title
                >

                <v-spacer></v-spacer>

                <v-btn icon>
                    <v-icon>mdi-filter</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
            </v-app-bar>
        </template>
        <v-container>
            <v-card class="d-flex justify-center mb-6" flat tile>
                <v-alert shaped dense outlined type="error">
                    {{ message }}
                    <inertia-link :href="route('login')">
                        aquí
                    </inertia-link>
                    para volver a iniciar sesión
                </v-alert>
            </v-card>
            <!-- <div class="d-flex flex-column justify-space-between align-center">
                <v-img  max-width="400" alt="error 503" src="/503.jpg" ></v-img>

            </div> -->
        </v-container>
    </error-layout>
</template>

<script>
import ErrorLayout from "@/Layouts/ErrorLayout";

export default {
    props: ["statusCode", "message"],
    components: {
        ErrorLayout,
    },
    data() {
        return {};
    },
    mounted() {},
};
</script>
