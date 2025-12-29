<?php

namespace App\GraphQL\Mutations;

use App\Models\City;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateCityMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCity',
        'description' => 'Создает новый город'
    ];

    public function type(): Type
    {
        // После создания возвращаем тип City, чтобы увидеть результат
        return GraphQL::type('City');
    }

    public function args(): array
    {
        // Описываем аргументы, которые нужно передать для создания
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'unique:cities,name']
            ],
            'slug' => [
                'name' => 'slug',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'unique:cities,slug']
            ],
        ];
    }

    public function resolve($root, $args)
    {
        // Логика сохранения в БД
        $city = new City();
        $city->name = $args['name'];
        $city->slug = $args['slug'];
        $city->save();

        return $city;
    }
}