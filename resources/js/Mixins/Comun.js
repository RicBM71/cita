let historico_urls = [];
module.exports = {
    methods: {
        getFechaHora: function(value) {
            return value == null ? '' : this.$moment(value).format('D/MM/YYYY H:mm:ss');
        },
        getDate: function(value) {
            return value == null ? '' : this.$moment(value).format('D/MM/YYYY');
        },
        getCurrencyFormat(value) {
            return value == null
                ? null
                : new Intl.NumberFormat('de-DE', {
                      style: 'currency',
                      currency: 'EUR',
                      minimumFractionDigits: 0,
                  }).format(parseFloat(value));
        },
        setMyHistoryUrl(url) {
            historico_urls.unshift(window.location.href);
        },
        ressetMyHistoryUrl(url) {
            historico_urls = [];
        },
        goBackUrl() {
            if (historico_urls.length == 0) this.$inertia.get(route('dashboard'));
            else {
                const url = historico_urls[0];
                historico_urls.shift();
                this.$inertia.get(url);
            }
        },
        allUrl() {
            console.log(historico_urls);
        },
    },
    computed: {
        getUltUrl() {
            return historico_urls[0];
        },
    },
};
