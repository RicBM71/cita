<template>
    <v-col cols="12" md="3" lg="3">
        <v-card max-width="450" class="mx-auto">
            <v-toolbar dense color="secondary">
                <v-toolbar-title>Bono</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-card
                    :img="computedImgBono"
                    class="d-flex mt-2 pr-6 justify-end align-center"
                    color="grey lighten-2"
                    flat
                    height="230"
                    tile
                >
                    <v-card class="pr-8 text-h6 justify-right" flat>
                        <span class="purple--text text-darken-4">BONO {{ bonos.bono }} </span><br />
                        ( {{ bonos.usadas }} de {{ bonos.sesiones }} )
                        <br />
                        <span class="text-caption">Caducidad: {{ bonos.fecha_caducidad }} </span><br />
                    </v-card>
                </v-card>

                <v-tooltip bottom v-for="(cita, i) in bonos.citas" :key="i" color="blue lighten-1">
                    <template v-slot:activator="{ on, attrs }">
                        <v-chip small outlined color="purple darken-3" class="ma-2" v-bind="attrs" v-on="on">
                            {{ cita.fecha_corta }}
                        </v-chip>
                    </template>
                    <span>{{ cita.nombre }} - {{ cita.hora }}</span>
                </v-tooltip>

                <!-- <v-row align="end" class="fill-height">
                    <v-col align-self="start" class="pa-0" cols="12">
                        <v-list-group v-for="(item, i) in bonos" :key="i" link :value="false" no-action sub-group>
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        <span v-if="!item.caducado" class="font-weight-bold">
                                            Bono {{ getDate(item.fecha_bono) }} - Caduca: {{ item.fecha_caducidad }}
                                        </span>
                                        <span v-else class="font-weight-bold"> Bono {{ getDate(item.fecha_bono) }}</span>
                                    </v-list-item-title>
                                </v-list-item-content>
                            </template>
                            <v-list-item v-for="(cita, i) in item.citas" :key="i" link>
                                <v-list-item-title>{{ cita.fecha }} - {{ cita.nombre }}</v-list-item-title>
                            </v-list-item>
                        </v-list-group>
                    </v-col>
                </v-row> -->
            </v-card-text>
        </v-card>
    </v-col>
</template>
<script>
export default {
    props: {
        bonos: {
            type: Object,
        },
    },
    computed: {
        computedImgBono() {
            return '/assets/bono_az' + this.bonos.usadas + '.jpg';
        },
    },
};
</script>

<style></style>
