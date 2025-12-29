<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quick-start', function () {
    // 1. Создаем или находим админа
    $user = User::updateOrCreate(
        ['email' => 'admin2@test.com'],
        [
            'name' => 'Admin',
            'password' => Hash::make('12345678'), // пароль будет: password
        ]
    );
    return "Пользователь (admin@test.com) и Дилер созданы! Теперь можно идти в /admin";
});

Route::get('/test-api', function () {
    $remoteUrl = 'https://api-used.ru/graphql';

    // Добавляем аргумент (site_id: 1) внутрь запроса
    $query = '
        query GetSite($id: Int!) {
            sites(site_id: $id) {
                id
                parent_site_id
                title
                slug
            }
        }
    ';

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post($remoteUrl, [
        'query' => $query,
        'variables' => [
            'id' => 763 // Подставьте сюда нужный ID сайта
        ]
    ]);

    if ($response->successful()) {
        return $response->json();
    }

    return "Ошибка сервера: " . $response->status();
});

// Route::get('/graphiql-panel', function () {
//     return app('graphql')->graphiql();
// });