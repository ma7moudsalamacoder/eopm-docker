<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\FakeCards;

class FakeCardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cards = [
            [
                "holder" => "Test User One",
                "card_number" => "4111111111111111",
                "cvv" => "123",
                "valid"=>"12/29",
                "type" => "visa",
                "status"=> "insufficient balance",
            ],
            [
                "holder" => "Test User Two",
                "card_number" => "5555555555554444",
                "cvv" => "456",
                "valid"=>"11/25",
                "type" => "mastercard",
                "status"=> "expired"
            ],
            [
                "holder" => "Test User Three",
                "card_number" => "4000000000000002",
                "cvv" => "777",
                "valid"=>"20/28",
                "type" => "visa",
                "status"=> "in use"
            ],
            [
                "holder" => "Test User Four",
                "card_number" => "5105105105105100",
                "cvv" => "444",
                "valid"=>"11/27",
                "type" => "mastercard",
                "status"=> "insufficient balance"
            ]
        ];
        foreach ($cards as $card) {
            FakeCards::create($card);
        }
        
    }
}
