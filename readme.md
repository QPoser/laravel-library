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

<hr>

<h3>Процесс создания</h3>

<p>Для начала, перед проектом нужно описать его техническое задание, и описать моменты, которые будут реализованы в нашем приложении:</p>

<ul>
    <li><b>Пользователи</b></li>
    <li>Авторизация/Регистрация</li>
    <li>Личный кабинет, в котором можно заниматься модерацией книг, изменить личные данные и т.д.</li>
    <li>Разделение ролей</li>
    <li><b>Админ-панель</b></li>
    <li>Просмотр и модерация всех книг с фильтрацией и поиском</li>
    <li>Просмотр и модерация пользователей</li>
    <li>Добавление категорий и писателей для книг</li>
    <li><b>Книги</b></li>
    <li>Просмотр книг, с усовершенствованным поиском с использованием ElasticSearch, и фильтрацией.</li>
    <li>Возможность оценки книг пользователями</li>
    <li>Система комментариев для книг</li>
    <li>Bundle для книг, возможность группировки книг</li>
    <li>Реализация площадки для независимых писателей, с возможностью подписки и поддержки.</li>
    <li><b>Дополнительные моменты</b></li>
    <li>Обращения к модераторам, блокировка нежелательного контента</li>
    <li>Что выяснится по ходу разработки...</li>
    <li><b>API для работы с онлайн-библиотекой (планируется)</b></li>
</ul>

<hr>

<h4>Пользователи</h4>

<p><b>Авторизация/Регистрация</b></p>

<p>Для создания базовой авторизации и регистрации нужно выполнить команду</p>
<pre>php artisan make:auth</pre>
<p>И применить уже созданные миграции</p>
<pre>php artisan migrate</pre>
<p>После данного трюка, у вас появится базовое меню со страницами входа и регистрации</p>

<p><b>Кастомизация регистрации</b></p>

<p>Основной момент, который нужно знать для кастомизации регистрации - это то, что App\User должен
быть унаследован от стандартного класса Illuminate\Foundation\Auth\User, который в свою очередь реализует интерфейсы
AuthenticatableContract, AuthorizableContract, CanResetPasswordContract. Если вы хотите написать свой класс юзера, напишите свою реализацию
этих интерфейсов, и замените класс стандартного юзера на свой в файле config/auth.php. Но в том случае, чтоб вмешаться в поведение стандартной аутентификации, вы должны переопределить стандартные методы 
из трейтов в контроллерах, как например методы login() и verify() в контроллере LoginController. Также можно использовать и другой метод изменения поведения стандартных контроллеров для аутентификации, 
для этого используйте методы authenticated() в контроллере LoginController и registered() в контроллере RegisterController, которые вызываются после входа и после регистрации пользователя.</p>

<br>

<p>Используем первый вариант, для добавления подтверждения электронной почты нового пользователя. Помимо переопределения контроллеров, нам нужно создать новые поля у пользователя, такие как
status, для определения статуса пользователя, и поля verify_code, которое будет нужно для хранения секретного кода из почтового сообщения. Для этого создадим миграцию с помощью команды</p>
<pre>php artisan make:migration add_user_verification</pre>
<a href="https://laravel.com/docs/5.6/migrations">Подробнее о миграциях.</a>

<br>

<p>Основной недостаток в случае, с изменением стандартного поведения - использование функции Auth::attempt(), которая валидирует данные пользователя из реквеста, и аутентифицирует его в системе.
 Возвращает bool в случае успеха или неудачи.</p>
 
<p>Добавим константы STATUS_WAIT и STATUS_ACTIVE в классе User, метод verify() для верификации пользователя, и два метода, проверяющих является ли пользователь подтвержденным isWait(), isActive(), и добавим
поле 'status' в массив $fillable:</p>
<pre>
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
<br>
    protected $fillable = [
        'name', 'email', 'password', 'status',
    ];
<br>
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }
<br>
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
<br>
    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }
</pre>

<hr>

<p>После данной процедуры, перепишем контроллеры в папке Auth:</p>

<b>LoginController</b>

<p>Первым изменим поведение контроллера входа, для этого нам нужно разобраться как он работает:</p>

<p>Изначально мы видим только использование трейта AuthenticatesUsers, переменную $redirectTo и строку в конструкторе
<pre>
$this->middleware('guest')->except('logout');
</pre>
которая означает то, что мы применяем стандартного посредника guest на все действия контроллера, за исключением logout.
Данный посредник не даст доступ аутентифицированным пользователям использовать действия данного контроллера. </p>

<p>Трейт AuthenticatesUsers использует ещё два трейта - RedirectsUsers и ThrottlesLogins. Первый из них реализует один метод - redirectPath(), который нужен 
для того, чтобы получить путь, на который контроллер сделает редирект. Второй же нужен для того, чтобы контролировать количество неудачных попыток входа. 
Сам же AuthenticatesUsers содержит методы, которые нужны для входа пользователя в систему.</p>

<p>Для реализации собственного контроллера входа на базе старого - вытащим методы login(), verify(), logout(), username(), showLoginForm() из
трейта AuthenticatesUsers. Так как мы сами реализуем нужные нам переходы в контроллерах, трейт RedirectsUsers нам больше не понадобится, его можно убрать.
По итогу получится, что мы вынесем методы и трейт ThrottlesLogins из AuthenticatesUsers в наш LoginController, использование AuthenticatesUsers убираем из контроллера.</p>

<p>Дальше мы должны изменить два метода - logout() и login(), и получаем:
<br>
<b>login()</b>
<pre>
public function login(Request $request)
{
    // Валидируем данные, которые пришли в переменной $request
    $this->validate($request, [
        'email' => 'required|string',
        'password' => 'required|string',
    ]);
    // Если количество неудачных попыток входа превышает допущенное значение, выкидываем стандартное исключение
    if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);
        $this->sendLockoutResponse($request);
    }
    <br>
    // Попытка авторизации, первым параметром передаем email и password из $request, вторым - true, если в $request есть переменная remember
    $authenticate = Auth::attempt(
        $request->only(['email', 'password']),
        $request->filled('remember')
    );
    <br>
    // Если вход прошёл успешно, присваиваем сессии новый идентификатор, очищаем количество попыток входа,
    // и если пользователь не активный, разлогиниваем его, и редиректим обратно с сообщением, что ему нужно подтвердить его аккаунт.
    // В случае, если пользователь активный - делаем редирект.
    if ($authenticate) {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        $user = Auth::user();
        if ($user->isWait()) {
            Auth::logout();
            return back()->with('error', 'You need to confirm your account. Please check your email.');
        }
        return redirect()->intended(route('home'));
    }
    <br>
    // Повышаем количество попыток входа
    $this->incrementLoginAttempts($request);
    <br>
    // Выдаём ошибку аутентификации
    throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
}
</pre>
<br>
<b>logout()</b>
<pre>
public function logout(Request $request)
{
    // Проверка входа
    Auth::logout();
    // Очищаем сессию 
    $request->session()->invalidate();
    // Редирект на главную страницу
    return redirect('/');
}
</pre>
</p>

<p>Напоследок затронем метод username(), данный по-умолчанию возвращает строку 'email', если вы хотите сделать вход через какое-то другое поле юзера,
замените данную строку например на 'user'.</p>

<hr>

<b>RegisterController</b>

<p>Стандартный контроллер регистрации выглядит проще, чем LoginController. Он включает в себя один трейт, который содержит метод для отображения формы, и
метод для регистрации, и два метода - для создания пользователя, и для валидации введенных данных. Данный контроллер можно полностью переписать на свою реализацию.

Для удобства используем разделение логики в контроллере, и вынесем код, отвечающий за регистрацию в отдельный сервис RegisterService.

Создадим его в пространстве имён App\Services\Auth\RegisterService, и создадим в нём два метода - register() и verify().</p>

<p>Метод register() должен регистрировать нового пользователя, отправлять сообщение на его email, и вызывать событие Registered. А метод verify() должен 
найти пользователя, и вызвать его метод verify().</p>

<p>В самом контроллере мы должны в конструкторе передать RegisterService, который подтянется с помощью DI в наш контроллер,
и реализовать два экшена - register() и verify(). И оставить вывод формы - showRegistrationForm(). Получаем: </p>

<pre>
	public function __construct(RegisterService $service)
    {
        $this->middleware('guest');
	    $this->service = $service;
    }

    public function showRegistrationForm()
    {
    	return view('auth.register');
    }


    protected function register(RegisterRequest $request)
    {
    	$this->service->register($request);

		return redirect()->route('login')
			->with('success', 'Check your email and click on the link to verify.');
    }

    public function verify($token)
    {
    	if (!$user = User::where('verify_token', $token)->first()) {
    		return redirect()->route('login')
			    ->with('error', 'Sorry your link cannot be identified.');
	    }

	    if ($user->isActive()) {
    		return redirect()->route('login')
			    ->with('error', 'Your email is already verified.');
	    }

	    try {
		    $this->service->verify($user->id);
		    return redirect()->route('login')
			    ->with('success', 'Your e-mail is verified. You can now login.');
	    } catch (\DomainException $e) {
    		return redirect()->route('login')->with('error', $e->getMessage());
	    }
    }
</pre>

<p>Таким образом мы получаем данные в контроллере, валидируем их, и передаем в сервис, после чего делаем редирект на нужную нам страницу.</p>

<p><b>Note: </b> Вы можете заметить в коде выше, что тут нет валидации параметров из Request, а вместо Request используется 
RegisterRequest. Всё дело в том, что валидация в laravel может быть выполнена ещё до обращения к контроллеру, для этого нужно создать свой собственный Request:</p>
<pre>php artisan make:request "Auth\RegisterRequest"</pre>
<p>В котором нужно прописать правила валидации в методе rules(), и заменить false на true в методе authorize():</p>
<pre>
class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    // Правила валидации
    public function rules()
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
</pre>

<p>Последний момент, который нужно разобрать - web.php, здесь нас интересуют два момента:
1. Auth::routes() - это метод, который создаёт пути до наших контроллеров аутентификации.
2. Для того, чтоб добавить путь для verify, нам нужно добавить в web.php конструкцию 
<pre>Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');</pre>
Проверить какие пути доступны в нашей системе можно с помощью команды
<pre>php artisan route:list</pre></p>

<hr>

<b>Личный кабинет</b>

<p>Для создания личного кабинета, нам нужно прежде всего создать его контроллер, поэтому создаём новый контроллер:</p>
<pre>php artisan make:controller "Cabinet\HomeController"</pre>
<p>Первым делом, мы должны были бы закрыть этот контроллер от незарегистрированных пользователей, для этого добавим посредника в конструктор:</p>
<pre>
    public function __construct()
    {
        $this->middleware('auth');
    }
</pre>
<p>После этого добавим ещё один контроллер для профиля:</p>
<pre>php artisan make:controller "Cabinet\ProfileController"</pre>
<p>Закроем его аналогичным посредником в конструкторе, как и в прошлом контроллере. После чего добавим три метода - index(), 
edit(), update().</p>

<p>Но контроллеры не активны по-умолчанию, как например в Yii2, для их работы нам нужно указать их пути в файле routes/web.php, и тут 
можно описать каждый путь отдельно, например так:</p>
<pre>
    Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet.home');
    Route::get('/cabinet/profile', 'Cabinet\ProfileController@index')->name('cabinet.profile.home');
    Route::get('/cabinet/profile/edit', 'Cabinet\ProfileController@edit')->name('cabinet.profile.edit');
    Route::put('/cabinet/profile/update', 'Cabinet\ProfileController@update')->name('cabinet.profile.update');
</pre>
<p>Но в таком случае приходится каждый раз прописывать префиксы в названии и пути, а также указывать неймспейс и прописывать посредников, 
поэтому можно объединить данные пути в группу:</p>
<pre>
    Route::group(
        [
            'prefix' => 'cabinet',
            'as' => 'cabinet.',
            'namespace' => 'Cabinet',
            'middleware' => ['auth'],
        ],
        function () {
            Route::get('/', 'HomeController@index')->name('home');
            Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
                Route::get('/', 'ProfileController@index')->name('home');
                Route::get('/edit', 'ProfileController@edit')->name('edit');
                Route::put('/update', 'ProfileController@update')->name('update');
            });    
        }
    );
</pre>
<p>Это будут эквивалентные записи, но во втором случае если нам нужно будет поменять префикс или неймспейс - мы можем поменять его в одном месте. 
Также во втором случае мы закрываем все пути посредником auth, и теперь их не нужно прописывать в конструкторе контроллеров.</p>
<p>Далее нам нужно добавить редактирование личных данных. Сделаем возможность поменять имя. Для этого нам нужно написать вручную шаблон для формы ввода, 
так как в laravel отсутствуют построители форм как в Yii2.</p>



