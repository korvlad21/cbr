Для запуска приложения необходимо скачать данный репозиторий и выполнить следующие действия
1. Переименуйте файл '.env.example' на '.env'
2. Установите на официальных сайтах Docker и Docker Compose
3. Запустите команду в консоли проекта: docker-compose up -d
4. Запустите команде в консоли проекта: docker-compose exec laravel.test php artisan migrate --seed. Если по каким-то причинам выдало ошибку попробуйте: docker-compose exec laravel.test php artisan migrate:refresh --seed
5. Перейдите по ссылке http://0.0.0.0/
6. В случае пустого белого экрана попробуйте ввести команду docker-compose exec laravel.test npm run build или docker-compose exec laravel.test npm run dev

