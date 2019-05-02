<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'name' => 'Philippines',
            'code' => 'PH',
            'created_at' => sqlDate()
        ]);

        $cities = [
            "Alaminos City", "Angeles City", "Antipolo City", "Bacolod City", "Bago City", "Baguio City", "Bais City", "Balanga City", "Batangas City", "Bayawan City", "Bisilig City", "Butuan City", "Cabanatuan City", "Cadiz City", "Cagayan de Oro City", "Calamba City", "Calapan City", "Calbayog City", "Caloocan City", "Candon City", "Canlaon City", "Cauayan City", "Cavite City", "Cebu City", "Cotabato City", "Dagupan City", "Danao City", "Dapitan City", "Davao City", "Digos City", "Dipolog City", "Dumaguete City", "Escalante City", "Gapan City", "General Santos City", "Gingoog City", "Himamaylan City", "Iligan City", "Iloilo City", "Iriga City",
            "Isabela City", "Island Garden City of Samal", "Kabankalan City", "Kidapawan City", "Koronadal City", "La Carlota City", "Laoag City", "Lapu-Lapu City", "Las Piñas City", "Legazpi City", "Ligao City", "Lipa City", "Lucena City", "Maasin City", "Makati City", "Malabon City", "Malaybalay City", "Malolos City", "Malolos City", "Mandaluyong City", "Mandaue City", "Manila", "Maragondon", "Marawi City", "Masbate City", "Muntinlupa City", "Naga City", "Olongapo City", "Ormoc City", "Oroquieta City", "Ozamis City", "Pagadian City", "Palayan City", "Legazpi City", "Parañaque City", "Pasay City", "Pasig City", "Passi City", "Puerto Princesa City", "Quezon City", "Roxas City", "Sagay City", "San Carlos City, Negros Occidental", "San Carlos City, Pangasinan", "San Fernando City, La Union", "San Fernando City, Pampanga", "San Jose City", "San Jose del Monte City", "San Pablo City", "Santa Rosa City", "Santiago City", "Muñ City", "Silay City", "Sipalay City", "Sorsogon City", "Surigao City", "Tabaco City", "Tacloban City", "Tacurong City",
            "Tagaytay City", "Tagbilaran City", "Tagum City", "Talisay City, Cebu", "Talisay City, Negros Occidental", "Tanauan City", "Tangub City", "Tanjay City", "Tarlac City", "Taguig City", "Toledo City", "Trece Martires City", "Tuguegarao City", "Urdaneta City", "Valencia City", "Valenzuela City", "Victorias City", "Vigan City", "Zamboanga City"
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => ucfirst($city),
                'country_id' => 1,
                'created_at' => sqlDate()
            ]);
        }
    }
}
