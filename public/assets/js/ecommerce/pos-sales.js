/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    // search for products
    WBPOS.searchProduct();
});

var WBPOS = (function () {
    var _container_product = jQ('#container_product');

    return {
        /**
         * Complete
         */
        complete: function () {
            console.log('Sending POS.');
        },

        /**
         * Search products
         */
        searchProduct: function () {
            _container_product.html('');
            jQ('#search_product').keypress(function (e) {
                if (e.which == 13) {
                    _container_product.html('');

                    var value = jQ(this).val();
                    if (!value) {
                        return;
                    }

                    WBPOS.queryProduct(value);
                    return false;
                }
            });
        },

        /**
         * Query for product on server
         *
         * @param query
         */
        queryProduct: function (query) {
            console.log('Searching form ' + query);

            var data = {search: query};
            var search_type = jQ('#search_type').val();
            if (search_type == 'name') {
                data = {name: query};
            } else if (search_type == 'item_code') {
                data = {item_code: query};
            } else if (search_type == 'sku') {
                data = {sku: query};
            }

            WBHelper.ajax({
                url: '/admin/product',
                type: 'get',
                data: data,
                success: function (response, textStatus, jqXHR) {
                    var view = '';

                    if (response.success) {
                        var data = response.data;

                        for (var i = 0; i < data.length; i++) {
                            view += WBPOS.viewProductListCard(data[i]);
                        }

                        _container_product.html((view) ? view : '<h4 class="text-center">No Product Found.</h4>');
                        WBPOS.btnAddToCart();
                    } else {
                        _container_product.html(view);
                    }
                }
            });
        },

        /**
         * Button for adding to cart
         *
         * @param id
         */
        btnAddToCart: function (id) {
            jQ('.add_to_sales_cart').off().on('click', function (e) {
                e.preventDefault();

                WBPOS.addToCart(jQ(this).attr('data-id'));
            });
        },

        /**
         * Add to cart submit to server
         *
         * @param id
         */
        addToCart: function (id) {
            console.log('Adding to cart: ' + id);
            _container_product.html('');
            jQ('#search_product').val('');

            WBHelper.ajax({
                url: '/cart/add/' + id,
                type: 'get',
                success: function (response, textStatus, jqXHR) {
                    // TODO refresh cart
                }
            });
        },

        viewCartList: function (data) {

        },

        /**
         * View list card template
         *
         * @param data
         * @returns {string}
         */
        viewProductListCard: function (data) {
            return '<div class="media"> \
                <div class="media-left"> \
                    <img class="media-object" src="' + data.cover + '" alt="' + data.name + '" width="64px"> \
                </div> \
                       \
                <div class="media-body"> \
                    <h5 class="media-heading">' + data.name + '</h5> \
                    <p>Price: ' + data.srp + ' Quantity: ' + data.qty + '</p> \
                    <a href="#" class="btn btn-block btn-primary btn-sm add_to_sales_cart" data-id="' + data.id + '">Add to cart</a> \
                </div> \
            </div>';
        }
    };
}());