<?php

namespace App\GraphQL\Types;

use App\Models\SiteFeed;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SiteFeedType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SiteFeed',
        'model' => SiteFeed::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
            ],
            'site' => [
                'type' => GraphQL::type('Site'), // Должен быть зарегистрирован SiteType
                'description' => 'Сайт, к которому привязан фид',
            ],
            'feed' => [
                'type' => GraphQL::type('Feed'), // Должен быть зарегистрирован FeedType
                'description' => 'Сам фид',
            ],
            'created_at' => [
                'type' => Type::string(),
            ],
        ];
    }
}