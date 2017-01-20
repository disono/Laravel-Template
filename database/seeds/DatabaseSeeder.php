<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(AuthorizationSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AuthorizationRoleSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(ExcludeSlugSeeder::class);
        $this->call(PageCategorySeeder::class);
        $this->call(PageSeeder::class);

        // ecommerce seeder
        $this->_ecommerce();
    }

    private function _ecommerce()
    {
        $this->_productCategory();
        $this->_paymentType();
        $this->_orders();
    }

    private function _productCategory()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('product_categories')->insertGetId([
                'name' => str_random(),
                'created_at' => sql_date()
            ]);
        }
    }

    private function _paymentType()
    {
        // payment types
        foreach ([
                     'COD', 'Bank Transfer'
                 ] as $row) {
            DB::table('payment_types')->insertGetId([
                'name' => $row,
                'description' => $row,

                'created_at' => sql_date()
            ]);
        }
    }

    private function _orders()
    {
        for ($i = 0; $i < 10; $i++) {
            $id = DB::table('orders')->insertGetId([
                'customer_id' => 1,

                'full_name' => str_random(),
                'billing_address' => str_random(),
                'shipping_address' => str_random(),

                'qty' => 1,
                'discount' => 1200,
                'shipping' => 1200,
                'tax' => 1200,
                'total' => 1200,

                'payment_type_id' => 1,

                'created_at' => sql_date()
            ]);

            for ($num = 0; $num < 10; $num++) {
                DB::table('ordered_items')->insertGetId([
                    'order_id' => $id,
                    'product_id' => 1,

                    'qty' => 12,
                    'srp' => 5,
                    'total' => 122,

                    'created_at' => sql_date()
                ]);
            }
        }
    }
}
