<?php

use App\Models\SearchValue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SearchValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $data = [
            [
                'name' => 'Trump',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'ISIS',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Esports',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Lady Gaga',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        SearchValue::query()->insert($data);
    }
}
