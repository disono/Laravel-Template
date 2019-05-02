/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

let WBToolbarPlugin = {
    install(Vue, options) {
        Vue.mixin({
            data: function () {
                return {
                    toolbar: {
                        selectedItems: []
                    }
                }
            }
        });

        /**
         * Check the item
         *
         * @param event
         */
        Vue.prototype.toolbarSelectItem = function (event) {
            let self = this;

            if (event.target.checked) {
                jQ('input[delete-data="toolbarSelectedItems"]').each(function (val, index) {
                    self.toolbar.selectedItems.push(jQ(this).val());
                });
            } else {
                self.toolbar.selectedItems = [];
            }
        };

        /**
         * Delete all selected items
         */
        Vue.prototype.toolbarDeleteSelected = function (event) {
            let self = this;

            if (!self.toolbar.selectedItems.length) {
                return;
            }

            WBServices.view.dialogs('delete', null, function (r) {
                let list = [];
                let index = 0;

                jQ('input[delete-data="toolbarSelectedItems"]').each(function (val, index) {
                    if (jQ(this).is(':checked')) {
                        list.push(jQ(this));
                    }
                });

                deleteSelectedItem();

                function deleteSelectedItem() {
                    if (!isDone()) {
                        self.toolbar.selectedItems = [];
                        return;
                    }

                    let id = '#parent_tr_' + list[index].val();
                    WBServices.http.delete(jQ('#parent_tr_del_' + list[index].val()).attr('href')).then(function (response) {
                        let parentTBody = jQ(id).parent();
                        jQ(id).remove();

                        if (parentTBody.children().length === 0) {
                            // refresh the page
                            location.reload();
                        }

                        index++;
                        deleteSelectedItem();
                    }).catch(function (error) {
                        index++;
                        deleteSelectedItem();
                    });
                }

                function isDone() {
                    return index <= (list.length - 1);
                }
            }, function (r) {

            });
        };
    }
};