<?php

namespace App\GraphQL\Types;

use App\Models\Feed;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FeedType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Feed',
        'description' => 'Информация о фиде (XML/YML)',
        'model' => Feed::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'ID фида',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Название фида',
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'Тип фида (Yandex, VK, Google)',
            ],
            'url' => [
                'type' => Type::string(),
                'description' => 'Ссылка на файл фида',
            ],
            'created_at' => [
                'type' => Type::string(),
                'resolve' => function ($model) {
                    return $model->created_at ? $model->created_at->toDateTimeString() : null;
                },
            ],
        ];
    }
}