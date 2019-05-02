<?php

use Illuminate\Database\Seeder;

class PageReportReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            'It\'s harassing someone I know',
            'Sexually explicit content',
            'Spam or a scam',
            'Violence or harmful behaviour',
            'Hate speech',
            'It\'s for selling regulated goods',
            'I think it\'s an unauthorised use of intellectual property'
                 ] as $reason) {
            DB::table('page_report_reasons')->insert([
                'name' => ucfirst($reason),
                'created_at' => sqlDate()
            ]);
        }
    }
}
