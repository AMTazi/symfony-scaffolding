<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

abstract class BaseFixture extends Fixture
{

    protected $faker;

    public function __construct()
    {
        $this->faker =  Factory::create();
    }


    protected function createMany(int $number, $tag, $callback)
    {
        for ($i = 1; $i <= $number; $i++) {

            $callback($i);
        }


    }
}