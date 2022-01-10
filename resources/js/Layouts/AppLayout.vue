<template>
    <v-app>
        <auth-app-bar id="mainlayout" :drawer.sync="drawer"></auth-app-bar>

        <v-main>
            <v-container id="container">
                <!-- <v-img src="/assets/fondo.jpg" max-height="125" height="25%"></v-img> -->
                <!-- Headings -->
                <!-- <headings :hasTitle="hasTitle" :hasSubtitle="hasSubtitle">
                    <template v-if="hasTitle" #title>
                        <slot name="title"></slot>
                    </template>
                    <template v-if="hasSubtitle" #subtitle>
                        <slot name="subtitle"></slot>
                    </template>
                </headings> -->

                <slot></slot>
            </v-container>
            <v-footer padless color="white">
                <v-col class="text-center primary--text" cols="12">
                    &copy; {{ new Date().getFullYear() }} —
                    <span>
                        Centro de fisioterapia Sanaval
                    </span></v-col
                >
            </v-footer>
        </v-main>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple></portal-target>
    </v-app>
</template>

<script>
import AuthAppBar from '@/Components/Layout/AuthAppBar';
import Headings from '@/Components/Headings';

export default {
    components: {
        AuthAppBar,
        Headings,
    },

    data() {
        return {
            showingNavigationDropdown: false,
            drawer: false,
            with: 300,
        };
    },
    mounted() {
        // TODO: con esto conseguimos que al pulsar el botón back del navegador, refresque la página
        // no funciona al avanzar. Ya veremos
        // const reloadOnBack = () => {
        //     this.$nextTick(() => {
        //         this.$inertia.reload({
        //             preserveScroll: true,
        //             preserveState: false,
        //         });
        //     });
        // };
        // window.addEventListener("popstate", reloadOnBack);
        // this.$once("hook:beforeDestroy", function () {
        //     window.removeEventListener("popstate", reloadOnBack);
        // });
    },
    computed: {
        hasTitle() {
            return !!this.$slots.title;
        },

        hasSubtitle() {
            return !!this.$slots.subtitle;
        },
    },

    watch: {
        drawer(val) {
            return val;
        },
    },

    methods: {
        logout() {
            axios.post(route('logout').url()).then((response) => {
                window.location = '/';
            });
        },
    },
};
</script>
<style scoped>
.mycontainer {
    margin-top: 4em;
    background: url('/assets/fondo.jpg') no-repeat center center;
    opacity: 0.5;
}
img {
    max-width: 120px;
}
</style>
