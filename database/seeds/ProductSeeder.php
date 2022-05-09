<?php

use Illuminate\Database\Seeder;
use App\Product;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Product::create([
             'name'=>"dummy test",
             'detail' =>'Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used as a placeholder before final copy is available',
              'price'=>34,
              'stripe_plan_id'=>'plan_LepkqcetBtYF3J'
            ]);
       
    }
}
