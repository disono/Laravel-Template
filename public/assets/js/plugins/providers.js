/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

let WBProviderPlugin = {
    install(Vue, options) {
        Vue.mixin({
            data: function () {
                return {
                    location: {
                        country_id: jQ('[data-input-country-id]').attr('data-input-country-id') ? jQ('[data-input-country-id]').attr('data-input-country-id') : '',
                        cities: []
                    }
                }
            }
        });

        Vue.prototype.onDeleteResource = WBServices.view.onDeleteResource;
        Vue.prototype.onReportResource = WBServices.view.onReport;

        Vue.prototype.onFormUpload = WBServices.form.onUpload;
        Vue.prototype.onFormPost = WBServices.form.onPost;
        Vue.prototype.onFormGet = WBServices.form.onGet;

        Vue.prototype.onCountrySelect = function (event, country_id) {
            let self = this;
            jQ('[data-input-city-remove="true"]').remove();

            if (country_id === '' || !country_id) {
                self.location.cities = [];
                resetSelectPicker();
                return;
            }

            WBServices.raw.get('/location/cities/' + country_id).then(function (response) {
                self.location.cities = response.data;
                resetSelectPicker();
            }).catch(function (error) {
                self.location.cities = [];
            });
        };

        function resetSelectPicker() {
            setTimeout(function () {
                jQ('.select_picker').selectpicker('destroy');
                jQ('.select_picker').selectpicker('render');
            }, 100);
        }
    }
};