<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rules = [
            ['name' => 'admin'],
            ['name' => 'owner'],
        ];
        Rule::factory(1)->createMany($rules);
    }
}
