<?php

namespace App\GraphQL\Types;

use App\Models\City; // Убедитесь, что у вас есть модель City
    use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CityType extends GraphQLType
{
    protected $attributes = [
        'name' => 'City',
        'description' => 'Информация о городе',
        'model' => City::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID города',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Название города',
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'URL-псевдоним города',
            ],
        ];
    }
}