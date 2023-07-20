Для запуска приложения необходимо скачать данный репозиторий и выполнить следующие действия
1. Переименуйте файл '.env.example' на '.env'
2. Установите на официальных сайтах Docker и Docker Compose
3. Введите следующую команду, чтобы добавить необходимые пакеты в ваш проект docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php82-composer:latest \
   composer install --ignore-platform-reqs
4. Запустите команду в консоли проекта: docker-compose up -d
5. В дополнительной консоли введите команду docker-compose exec laravel.test npm install
6. В этой же консоли введите docker-compose exec laravel.test npm run build или docker-compose exec laravel.test npm run dev
7. Запустите команде в консоли проекта: docker-compose exec laravel.test php artisan migrate --seed. Если по каким-то причинам выдало ошибку попробуйте: docker-compose exec laravel.test php artisan migrate:refresh --seed
8. Перейдите по ссылке этой http://0.0.0.0/ либо по ссылке которая появится в консоли
