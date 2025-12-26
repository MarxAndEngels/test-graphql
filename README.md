docker compose up -d --build

docker compose exec app php artisan key:generate

docker compose exec app php artisan db:seed

docker compose exec app php artisan storage:link