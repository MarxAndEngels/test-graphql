<?php

namespace App\GraphQL\Queries;

use App\Models\SiteFeed;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class SiteFeedsQuery extends Query
{
    protected $attributes = [
        'name' => 'siteFeeds',
    ];

    public function type(): Type
    {
        // Указываем, что возвращаем список типа SiteFeed
        return Type::listOf(GraphQL::type('SiteFeed'));
    }

    public function resolve($root, $args)
    {
        // Подгружаем все связи сразу (Eager Loading)
        return SiteFeed::with(['site.dealer.city', 'feed'])->get();
    }
}