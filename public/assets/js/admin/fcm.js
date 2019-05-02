/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

Vue.use(WBProviderPlugin);

new Vue({
    el: '#WBApp',

    mounted: function () {
        // initialize libraries and non-vue codes (/public/assets/js/vendor/initialize.js)
        WBInitialize();
    },

    data: {
        frmAdminFCM: {
            type: '',
            topic_name: '',
            token: ''
        }
    },

    methods: {
        frmAdminFCMOnTypeChange() {
            if (this.frmAdminFCM.type === 'topic') {
                this.frmAdminFCM.topic_name = '';
            } else if (this.frmAdminFCM.type === 'token') {
                this.frmAdminFCM.token = '';
            }
        }
    }
});

