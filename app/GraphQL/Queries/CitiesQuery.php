<?php

namespace App\GraphQL\Queries;

use App\Models\City;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class CitiesQuery extends Query
{
    protected $attributes = [
        'name' => 'cities',
    ];

    public function type(): Type
    {
        // Возвращаем список городов
        return Type::listOf(GraphQL::type('City'));
    }

    public function resolve($root, $args)
    {
        return City::all();
    }
}