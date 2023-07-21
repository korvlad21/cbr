Для запуска приложения необходимо скачать данный репозиторий и выполнить следующие действия
1. Переименуйте файл '.env.example' на '.env'
2. Установите на официальных сайтах Docker и Docker Compose
3. Для выполнения следующих команд необходимо перейти в терминал проекта (рекомендуется использовать PhpStorm или Visual Studio Code). 
4. Введите следующую команду, чтобы добавить необходимые пакеты в ваш проект docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php82-composer:latest \
   composer install --ignore-platform-reqs
5. Запустите команду в терминале проекта: docker-compose up -d
6. В новом терминале введите команду docker-compose exec laravel.test npm install
7. В этом же терминале введите docker-compose exec laravel.test npm run build или docker-compose exec laravel.test npm run dev
8. Запустите команде в терминале проекта: docker-compose exec laravel.test php artisan migrate --seed. Если по каким-то причинам выдало ошибку попробуйте: docker-compose exec laravel.test php artisan migrate:refresh --seed
9. Перейдите по ссылке этой http://localhost/ либо по ссылке которая появится в терминале в ходе выполнения пункта 5.
