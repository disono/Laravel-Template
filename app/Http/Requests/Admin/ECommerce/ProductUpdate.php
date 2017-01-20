<?php

namespace App\Http\Requests\Admin\ECommerce;

use App\Http\Requests\Admin\AdminRequest;

class ProductUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:products,id',
            'product_category_id' => 'required|integer|exists:product_categories,id',

            'name' => 'required|max:100',
            'description' => 'required|max:5000',
            'features' => 'max:5000',
            'custom_fields' => 'max:20000',
            'sku' => 'max:100',

            'is_featured' => 'integer|in:0,1',
            'is_latest' => 'integer|in:0,1',
            'is_sale' => 'integer|in:0,1',

            'is_qty_required' => 'integer|in:0,1',
            'qty' => 'integer',

            'srp' => 'numeric',
            'srp_discounted' => 'numeric',

            'is_taxable' => 'integer|in:0,1',
            'is_discountable' => 'integer|in:0,1',

            'is_disabled' => 'integer|in:0,1',
            'is_draft' => 'integer|in:0,1',
        ];
    }
}
