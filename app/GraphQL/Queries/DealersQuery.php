<?php

namespace App\GraphQL\Queries;

use App\Models\Dealer;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class DealersQuery extends Query
{
    protected $attributes = [
        'name' => 'dealers',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Dealer'));
    }

    public function resolve($root, $args)
    {
        // Загружаем вместе с городом 
        return Dealer::with('city')->get();
    }
}