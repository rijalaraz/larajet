<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Database\Factories\OrderFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        UserFactory::new()
            ->withPersonalTeam()
            // ->unverified()
            ->count(10)
            ->has(
                OrderFactory::new()
                    ->count(3)
                    ->hasAttached(
                        Product::factory()->count(5), [
                            'total_price' => rand(100, 500),
                            'total_quantity' => rand(1, 3)
                        ]
                    )
            )
            ->create();
    }
}
