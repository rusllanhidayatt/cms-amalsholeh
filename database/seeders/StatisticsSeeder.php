<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistic;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class StatisticsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('statistics')->truncate(); // kosongkan tabel dulu

        $faker = Faker::create();

        foreach (range(1, 2) as $i) {
            Statistic::create([
                'event' => $faker->randomElement(['page_view', 'button_click', 'form_submit']),
                'ip'    => $faker->ipv4,
                'user_agent'    => $faker->userAgent,
                'utm_source'    => $faker->optional()->randomElement(['google', 'facebook', 'newsletter', 'instagram']),
                'utm_medium'    => $faker->optional()->randomElement(['cpc', 'email', 'social']),
                'utm_campaign'  => $faker->optional()->word,
                'created_at'    => $faker->dateTimeBetween('-2 days', 'now'),
            ]);
        }
    }
}
