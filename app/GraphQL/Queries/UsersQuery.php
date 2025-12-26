<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'users',
    ];

    public function type(): Type
    {
        // Указываем, что запрос возвращает список (array) типа User
        return Type::listOf(GraphQL::type('User'));
    }

    // 1. Описываем доступные аргументы
    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(), // Тип данных - целое число
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string(),
            ]
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return User::where('id', $args['id'])->get();
        }
        // Здесь мы просто берем всех пользователей из БД через Eloquent
        return User::all();
    }
}