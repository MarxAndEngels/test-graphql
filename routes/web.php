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

// Route::get('/graphiql-panel', function () {
//     return app('graphql')->graphiql();
// });