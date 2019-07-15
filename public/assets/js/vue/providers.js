/**
 * @author              Archie Disono (webmonsph@gmail.com)
 * @link                https://github.com/disono/Laravel-Template
 * @lincense            https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright           Webmons Development Studio
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

            if (!country_id) {
                self.location.cities = [];
                _resetSelectPicker();
                return;
            }

            WBServices.raw.get('/location/cities/' + country_id).then(function (response) {
                self.location.cities = response.data;
                _resetSelectPicker();
            }).catch(function (e) {
                self.location.cities = [];
            });
        };

        Vue.prototype.onImageSelect = function (input, img) {
            jQ(input).off().click();

            jQ(input).off().change(function () {
                let me = this;

                if (me.files && me.files[0]) {
                    // allowed extensions
                    let fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
                    if (jQ.inArray(jQ(me).val().split('.').pop().toLowerCase(), fileExtension) === -1) {
                        jQ(me).val('');
                        swal("Oops!", "Only formats are allowed: " + fileExtension.join(', '), "error");
                        return;
                    }

                    // show placeholder
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        jQ(img).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(me.files[0]);
                }
            });
        };

        Vue.prototype.onSelectChangeSubmitForm = function ($event, formId) {
            jQ(formId).submit();
        };

        function _resetSelectPicker() {
            // reinitialized select picker
            setTimeout(function () {
                jQ('.select_picker').each(function (i, obj) {
                    let attrStyle = jQ(this).parent().parent().find('.btn.dropdown-toggle').attr('style');
                    jQ(this).selectpicker('destroy');
                    jQ(this).selectpicker('render');

                    if (attrStyle === 'background-color: #ffebee !important; color: #FF5A58 !important;') {
                        jQ(this).parent().parent().find('.btn.dropdown-toggle').attr('style', 'background-color: #ffebee !important; color: #FF5A58 !important;');
                    }
                });
            }, 50);
        }
    }
};