<?php

namespace App\GraphQL\Types;

use App\Models\Dealer;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class DealerType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Dealer',
        'description' => 'Информация о дилере',
        'model' => Dealer::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'title' => [
                'type' => Type::string(),
            ],
            'slug' => [
                'type' => Type::string(),
            ],
            'city' => [
                'type' => GraphQL::type('City'),
                'description' => 'Город, в котором находится дилер',
            ],
        ];
    }
}