<template>
    <v-app-bar dense :clipped-left="$vuetify.breakpoint.lgAndUp" app color="primary" dark>
        <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
            <span>{{ appName }}</span>
        </v-toolbar-title>
        <v-spacer />

        <v-btn icon @click="dashboard">
            <v-icon>mdi-home</v-icon>
        </v-btn>

        <v-btn @click="profile()" icon v-if="$page.props.jetstream.managesProfilePhotos">
            <v-avatar size="32px">
                <img class="img-fluid" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
            </v-avatar>
        </v-btn>

        <v-btn icon @click="Logout">
            <v-avatar size="32px" tile>
                <v-icon>mdi-exit-to-app</v-icon>
            </v-avatar>
        </v-btn>
    </v-app-bar>
</template>

<script>
export default {
    props: {
        drawer: Boolean,
    },
    computed: {
        currentRoute() {
            return !(this.$inertia.page.url == '/dashboard');
        },
    },
    methods: {
        closeDrawer() {
            this.$emit('update:drawer', !this.drawer);
        },
        abrir(name) {
            //this.drawer = false;
            this.ressetMyHistoryUrl();
            this.show_loading = true;
            this.$inertia.get(route(name));
        },
        dashboard() {
            this.ressetMyHistoryUrl();
            this.show_loading = true;
            this.$inertia.get(route('dashboard'));
        },
        profile() {
            this.$inertia.get(route('profile.show'));
        },
        Logout() {
            this.ressetMyHistoryUrl();
            this.snackbar_text = 'Logout...';
            this.snackbar = true;

            this.$inertia.post(
                route('logout'),
                {},
                {
                    onFinish: () => (this.snackbar = false),
                }
            );
            window.location.href = 'https://sanaval.com';
        },
    },
};
</script>
