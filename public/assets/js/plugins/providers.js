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
                return _data
            }
        });

        Vue.prototype.onDeleteResource = WBServices.view.onDeleteResource;
        Vue.prototype.onReportResource = WBServices.view.onReport;

        Vue.prototype.onFormUpload = WBServices.form.onUpload;
        Vue.prototype.onFormPost = WBServices.form.onPost;
        Vue.prototype.onFormGet = WBServices.form.onGet;

        /**
         * On country select fetch and set city list on select form
         *
         * @param event
         * @param country_id
         */
        Vue.prototype.onCountrySelect = function (event, country_id) {
            let self = this;
            jQ('[data-input-city-remove="true"]').remove();

            if (!country_id) {
                self.location.cities = [];
                _resetSelectPicker();
                return;
            }

            WBServices.raw.get('/location/cities/' + country_id).then(function (response) {
                self.location.cities = response.data;
                _resetSelectPicker();
            }).catch(function (error) {
                self.location.cities = [];
            });
        };

        /**
         * On select change submit form
         *
         * @param event
         * @param id
         */
        Vue.prototype.onSelectChangeSubmitForm = function (event, id) {
            jQ(id).submit();
        };

        /**
         * Image selector input with placeholder
         *
         * @param input
         * @param img
         */
        Vue.prototype.imgSelect = function (input, img) {
            jQ(input).off().click();

            jQ(input).off().change(function () {
                let me = this;

                if (me.files && me.files[0]) {
                    let fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
                    if (jQ.inArray(jQ(me).val().split('.').pop().toLowerCase(), fileExtension) === -1) {
                        jQ(me).val('');
                        swal("Oops!", "Only formats are allowed : " + fileExtension.join(', '), "error");
                        return;
                    }

                    let reader = new FileReader();

                    reader.onload = function (e) {
                        jQ(img).attr('src', e.target.result);
                    };

                    reader.readAsDataURL(me.files[0]);
                }
            });
        };

        function _resetSelectPicker() {
            setTimeout(function () {
                jQ('.select_picker').selectpicker('destroy');
                jQ('.select_picker').selectpicker('render');
            }, 100);
        }

        let _data = {
            location: {
                country_id: jQ('[data-input-country-id]').attr('data-input-country-id') ? jQ('[data-input-country-id]').attr('data-input-country-id') : '',
                cities: []
            }
        };
    }
};