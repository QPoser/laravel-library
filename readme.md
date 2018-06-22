<h1>Онлайн-библиотека Laravel</h1>
<hr>

<h3>Установка</h3>

<p>Этих инструментов достаточно для начала работы с фреймворком:</p>

<ul>
    <li><b>Установка проекта:</b></li>
    <li>composer create-project --prefer-dist laravel/laravel library.loc</li>
    <li><b>Установка пакета для хлебных крошек:</b></li>
    <li>composer require davejamesmiller/laravel-breadcrumbs:5.x</li>
    <li><b>Установка дебаг-бара и IDE-хелпера:</b></li>
    <li>composer require barryvdh/laravel-debugbar --dev</li>
    <li>composer require barryvdh/laravel-ide-helper --dev</li>
</ul>

<hr>

<h3>Старт проекта</h3>
<div>
    <p><b>Настройка ide-helper:</b></p>
    <p>Для автоматической генерации phpDoc для фасадов Laravel нужно прописать:</p>
    <p><pre>"scripts":{
            "post-update-cmd": [
                "Illuminate\\Foundation\\ComposerScripts::postUpdate",
                "php artisan ide-helper:generate",
                "php artisan ide-helper:meta"
            ]
        }</pre></p>
    <p>Затем нужно скопировать из папки vendor/barryvdh/laravel-ide-helper/config файл ide-helper.php в свою папку config, и в нем изменить строку</p>
    <p><pre>'include_fluent' => true</pre></p>
    <p>После этого выполните команду php artisan ide-helper:generate</p>    
    <p><b>Для проверки результата, зайдите в файл routes/web.php, и проверьте что PhpStorm не ругается, что класс Route отсутствует</b></p>    
</div>
<hr>
<div>
    <p><b>Запуск проекта:</b></p>
    <p>Запустить проект вы можете несколькими разными способами:</p>
    <ul>
        <li>php artisan serve - команда запускает локальный сервер, если у вас на машине уже установлен LAMP/LNMP</li>
        <li>Homestead/Vagrant - выделенная виртуальная машина на базе Vagrant, в Homestead уже загружен весь набор нужных инструментов</li>
        <li>Docker - запуск проекта с использованием Docker</li>
    </ul> 
    <hr>
    <p>Первые два способа написаны в документации к Laravel, поэтому рассмотрим способ запуска проекта через Docker:</p>
    <span>Всё, что нам нужно для запуска проекта на Docker - это файл docker-compose.yml,
     и установленный в системе Docker с пакетным менеджером Docker-compose.
    </span>
    <br>
    <span>Структура файла docker-compose.yml состоит из версии файла(version), и сервисов(services):
    <pre>
        version: '2'
        services:
    </pre>
    </span>
    <br>
    <span>В секции services описываются сервисы(контейнеры), которые нам нужны, из структуры описания нам нужно знать следующее:</span>
    <pre>
        services: - главная секция, в которой описаны контейнеры
          nginx: - название сервиса
            build: - настройки сборки контейнера
              context: ./   - путь, по которому будет искаться dockerfile, путь к которому описан ниже
              dockerfile: docker/nginx.docker - путь, относительный context, по которому находится dockerfile
            volumes: - линк файлов на нашей машине с рабочей дерикторией в контейнере.
              - ./:/var/www
              - ./docker/nginx/ssl:/etc/nginx/ssl
            ports: - порт, который занимает Docker на нашей машине (все запросы которые идут на 8080 порт нашей машины,
             транслируются на порт 443 в docker).
              - "8080:443"
            links: - связь контейнера php-fpm с нашим контейнером nginx.
              - php-fpm
          php-fpm: - название контейнера
            build: - настройки сборки контейнера
              context: ./
              dockerfile: docker/php-fpm.docker
            volumes: - линк файлов на нашей машине с рабочей директорией в контейнере
              - ./:/var/www
            links: - связь контейнера mysql с нашим контейнером php-fpm.
              - mysql
            environment: - переопределение параметров из .env файла для данного контейнера
              - "DB_PORT=3306"
              - "DB_HOST=mysql"
          mysql: - название контейнера
            image: mysql:5.7 - пакет, с которого docker-compose загрузит образ контейнера.
            // dockerfile такого сервиса будет выглядеть так:
            // https://github.com/docker-library/mysql/blob/fc3e856313423dc2d6a8d74cfd6b678582090fc7/5.7/Dockerfile
            volumes: - линк файлов на нашей машине с рабочей директорией в контейнере
              - ./storage/docker/mysql:/var/lib/mysql
            environment: - переопределение параметров из .env файла для данного контейнера
              - "MYSQL_ROOT_PASSWORD=secret"
              - "MYSQL_USER=app"
              - "MYSQL_PASSWORD=secret"
              - "MYSQL_DATABASE=app"
            ports: - порт, который занимает докер на нашей машине
              - "33061:3306"    
    </pre>
    <p>После написания такого файла используем команду</p>
    <pre>sudo docker-compose up --build</pre>
    <p>Следующим шагом проверим, что наш сайт доступен по ссылке https://localhost:8080/, если вы работаете на linux, laravel начнет ругаться, что у вас нет
    доступа к папке storage, поэтому нужно дать права на эту папку с помощью команды "sudo chmod -R 777 storage"<p>
     
</div>


