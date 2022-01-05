<template>
    <v-col cols="12" md="3" lg="3">
        <v-card max-width="450" class="mx-auto">
            <v-toolbar dense color="secondary">
                <v-toolbar-title>Contacto</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-img height="100%" src="/assets/bono3.jpg">
                    <v-list-item class="mt-2">
                        <v-list-item-icon>
                            <v-icon color="light-blue darken-4">mdi-clock</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title class="font-weight-medium">
                            <p>L-J: de 9h a 14h - 16.30 a 20h</p>
                            <p>Viernes: de 9h a 14:30</p>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="primary">mdi-phone</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title class="font-weight-medium">
                            <a class="body-1 text-decoration-none black--text" href="tel:+34917398708">91 739 87 08</a>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="green">mdi-whatsapp</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title class="font-weight-medium">
                            <a class="body-1 text-decoration-none black--text" href="#" @click="sendWhatsApp">634 23 48 15</a>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon color="blue-grey">mdi-email</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title class="font-weight-medium">
                            <a class="body-1 text-decoration-none black--text" href="mailto:info@sanaval.com">info@sanaval.com</a>
                        </v-list-item-title>
                    </v-list-item>
                </v-img>
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
export default {
    methods: {
        sendWhatsApp() {
            const ws = 'https://api.whatsapp.com/send?phone=34634234815';

            window.open(ws, 'Enviar WhatsApp', 'toolbar=no,resizable=no, width=400, height=500, scrollbar=no, status=no');
        },
    },
};
</script>

<style></style>
