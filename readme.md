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
