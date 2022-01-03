<template>
    <v-col cols="12" md="3" lg="3">
        <v-card max-width="450" class="mx-auto">
            <v-toolbar dense color="cyan lighten-5">
                <v-toolbar-title>Últimas citas</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-img src="/assets/bono3.jpg">
                    <v-list-item v-for="(item, i) in citas" :key="i" link>
                        <v-list-item-icon>
                            <v-icon color="orange" v-if="item.estado_id == 1">mdi-alarm</v-icon>
                            <v-icon color="blue" v-if="item.estado_id == 2 || item.estado_id == 3">mdi-check-all</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title class="font-weight-medium">
                            <span class="green--text" v-if="item.bono.numero_bono">(B)</span>
                            {{ item.fecha }}
                        </v-list-item-title>
                    </v-list-item>
                </v-img>
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
export default {
    props: {
        citas: {
            type: Array,
        },
    },
};
</script>

<style></style>
