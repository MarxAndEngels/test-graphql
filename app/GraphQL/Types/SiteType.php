<?php

namespace App\GraphQL\Types;

use App\Models\Site;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SiteType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Site',
        'description' => 'Информация о сайте и его зеркалах',
        'model' => Site::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'url' => [
                'type' => Type::string(),
            ],
            'favicon_image' => [
                'type' => Type::string(),
            ],
            'is_main' => [
                'type' => Type::boolean(),
            ],
            'is_active' => [
                'type' => Type::boolean(),
            ],
            'parent' => [
                'type' => GraphQL::type('Site'), 
                'description' => 'Главный сайт для этого зеркала',
            ],
            'dealer' => [
                'type' => GraphQL::type('Dealer'),
            ],
        ];
    }
}