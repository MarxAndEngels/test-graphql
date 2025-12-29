<?php

namespace App\GraphQL\Queries;

use App\Models\Feed;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class FeedsQuery extends Query
{
    protected $attributes = [
        'name' => 'feeds',
        'description' => 'Получает список всех фидов'
    ];

    public function type(): Type
    {
        // Указываем, что возвращаем список (оборачиваем в listOf)
        return Type::listOf(GraphQL::type('Feed'));
    }

    public function resolve($root, $args)
    {
        // Здесь происходит сам запрос к БД через Eloquent
        return Feed::all();
    }
}