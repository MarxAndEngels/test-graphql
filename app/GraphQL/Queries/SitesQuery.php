<?php

namespace App\GraphQL\Queries;

use App\Models\Site;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class SitesQuery extends Query
{
    protected $attributes = [
        'name' => 'sites',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Site'));
    }

    public function resolve($root, $args)
    {
        // Используем eager loading (with), чтобы не было проблем N+1 при загрузке связей
        return Site::with(['parent', 'dealer'])->get();
    }
}