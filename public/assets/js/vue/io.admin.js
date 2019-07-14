/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

Vue.use(WBProviderPlugin);
Vue.use(WBToolbarPlugin);

new Vue({
    el: '#WBApp',

    mounted: function () {
        // initialize libraries and non-vue codes (/public/assets/js/vendor/initialize.js)
        WBInitialize();
    },

    data: {
        frmAdminIO: {
            type: jQ('#type').val(),
            topic_name: jQ('#topic_name').val(),
            token: jQ('#token').val()
        }
    },

    updated: function () {

    },

    methods: {
        frmAdminIOOnTypeChange() {
            if (this.frmAdminIO.type === 'topic') {
                this.frmAdminIO.topic_name = '';
                jQ('#topic_name').val('');
            } else if (this.frmAdminIO.type === 'token') {
                this.frmAdminIO.token = '';
                jQ('#token').val('');
            }

            // reinitialized select picker
            WBJSOnInit();
        }
    }
});

