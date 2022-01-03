<template>
    <v-app id="inspire">

        <v-main>
            <!-- Page Heading -->
            <header v-if="$slots.header">
                <div class="my-4 mx-10">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>

            <!-- Modal Portal -->
            <portal-target name="modal" multiple> </portal-target>
        </v-main>
    </v-app>
</template>
<script>
export default {
    components: {},
    computed: {
        currentRoute() {
            return !(window.location.pathname == "/dashboard");
        },
    },
    data: () => ({

    }),
    mounted() {
        
    },

    methods: {
    },
};
</script>
