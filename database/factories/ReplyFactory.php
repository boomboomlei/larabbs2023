<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition()
    {

        $sentence=$this->faker->sentence();
        return [
            "user_id" => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
            "topic_id"=>rand(1,100),
            "content"=>$sentence,
        ];
    }
}
