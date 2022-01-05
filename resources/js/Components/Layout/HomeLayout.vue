<template>
    <v-app id="inspire">
        <section id="welcome" class="overflow-hidden">
            <v-row no-gutters>
                <v-col class="hidden-sm-and-down" md="4" offset-lg="1">
                    <v-img src="/assets/welcome.png" height="100vh" />
                </v-col>

                <v-col class="align-content-space-between layout wrap" cols="12" md="6" offset-lg="1">
                    <div style="transform: rotate(180deg) translateY(25%)">
                        <v-img
                            class="grow hidden-sm-and-down"
                            contain
                            max-height="200"
                            width="100%"
                            position="top right"
                            src="/assets/bubbles1.png"
                        />
                    </div>
                    <v-row align="center" justify="center">
                        <v-col cols="10" md="6">
                            <slot></slot>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </section>
    </v-app>
</template>

<script>
export default {
    components: {},

    data() {
        return {};
    },
};
</script>
