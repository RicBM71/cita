
module.exports = {
    methods: {
        hasRole: function (role) {
            return this.$page.props.roles.indexOf(role) >= 0 ? true : false;
        },
        hasPermiso: function (permiso) {
            return this.$page.props.permisos.indexOf(permiso) >= 0 ? true : false;
        },
    },
    computed: {
        currentUser() {
            
            return this.$page.props.user;
        },
        appName() {
            return this.$page.props.appName;
        },
        msgSuccess() {
            return this.$page.props.flash.success;
        }
    }

}
