<h1>Онлайн-библиотека Laravel</h1>
<hr>

<h3>Запуск проекта:</h3>

<pre>make docker-build</pre>

<hr>

<h3>Процесс создания</h3>

<p>Для начала, перед проектом нужно описать его техническое задание, и описать моменты, которые будут реализованы в нашем приложении:</p>

<ul>
    <li><a href="#setup"><b>Установка</b></a></li>
    <li><a href="#start_project"><b>Старт проекта</b></a></li>
    <li><a href="#configure_ide_helper">Настройка ide-helper</a></li>
    <li><a href="#up_project">Запуск проекта + Docker</a></li>
    <li><a href="#users"><b>Пользователи</b></a></li>
    <li><a href="#authorize">Авторизация/Регистрация</a></li>
    <li><a href="#cabinet">Личный кабинет, в котором можно заниматься модерацией книг, изменить личные данные и т.д.</a></li>
    <li><a href="#roles">Разделение ролей</a></li>
    <li><a href="#admin-panel"><b>Админ-панель</b></a></li>
    <li><a href="#admin-books">Просмотр и модерация всех книг с фильтрацией и поиском</a></li>
    <li><a href="#admin-users">Просмотр и модерация пользователей</a></li>
    <li><a href="#admin-genres-authors">Добавление жанров и писателей для книг</a></li>
    <li><a href="#books"><b>Книги</b></a></li>
    <li><a href="#books_view">Просмотр книг, с поиском и фильтрацией.</a></li>
    <li><a href="#reviews">Система рецензий для книг</a></li>
    <li><a href="#bundles">Bundle для книг, возможность группировки книг</a></li>
    <li><a href="#independent_writers">Реализация площадки для независимых писателей, с возможностью подписки и поддержки.</a></li>
    <li><a href="#additionals"><b>Дополнительные моменты</b></a></li>
    <li><a href="#breadcrumbs">Хлебные крошки</a></li>
    <li><a href="#elasticsearch">Усовершенствованный поиск с использованием ElasticSearch</a></li>
    <li><a href="#appeals">Обращения к модераторам, блокировка нежелательного контента</a></li>
    <li><a href="#pagination">Пагинация</a></li>
    <li><a href="#events">Оповещение пользователей о новой книге/бандле от независимого разработчика (Работа с событиями).</a></li>
    <li><a href="#api"><b>API для работы с онлайн-библиотекой</b></api></li>
</ul>

<hr>

<h3 id="setup">Установка</h3>

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

<h3 id="start_project">Старт проекта</h3>
<div>
    <p id="configure_ide_helper"><b>Настройка ide-helper:</b></p>
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
    <p id="up_project"><b>Запуск проекта:</b></p>
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

<h3 id="users">Пользователи</h3>

<p id="authorize"><b>Авторизация/Регистрация</b></p>

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

<b id="cabinet">Личный кабинет</b>

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
<hr>
<p>Далее мы разберемся как добавить в личный кабинет фотографию пользователя, для этого нам нужно понять как работать с файлами в laravel.</p>
<p>В laravel есть множество функций, которые позволяют работать с файлами из request. По умолчанию все настройки по загрузке файлов находятся 
в файле config/filesystems.php. Но нам нужно сделать фалидацию файлов по типу, загрузить изображение на сервер, и показать их пользователю, раберем всё по шагам:</p>
<p>Но перед началом, нужно создать миграцию, и добавить поле 'personal_photo' в таблицу 'users'.</p>
<strong>1. Валидация файлов из $request</strong>
<p>Для валидации файлов из $request мы должны использовать конструкцию:</p>
<pre>
        $this->validate($request, [
            'personal_photo' => 'mimes:jpeg,png',
        ]);
</pre>
<strong>2. Загрузка изображения на сервер</strong>
<p>Для загрузки изображения на сервер мы используем данный код:</p>
<pre>
        if ($request->hasFile('personal_photo') && $request->file('personal_photo')->isValid()) { // проверяем что файл нормально загружен на сервер
            // загружаем файл на сервер с помощью функции store($path, $options), для загрузки файла по пути storage/app/public/avatars.
            // второй параметр - название диска из массива disks в файле config/filesystems.php.
            $path = $request->file('personal_photo')->store('avatars', 'public');
            $user->update(['personal_photo' => $path]);
        }
</pre>
<strong>3. Отображение в публичной части</strong>
<p>По-умолчанию папка storage недоступна в публичной части, и для решения этой проблемы можно своздать символическую ссылку на папку 
storage/app/public, и разместить её в папке public/storage. Именно такое решение и предлагает laravel, и для этого нужно использовать команду:</p>
<pre>php artisan storage:link</pre>
<p>Но тут есть важный момент, что если вы работаете на docker/vagrant, то вам нужно выполнить данную команду прямо из виртуальной системы, поэтому 
для применения данной команды из под Docker используйте команду:</p>
<pre>sudo docker-compose exec php-cli php artisan storage:link</pre>
<p><b>Note: </b>По-умолчанию nginx позволяет загружать на сервер файлы с весом максимум 1Mb, для увеличения этого значения пропишите в default.conf следующую строчку:</p>
<pre>client_max_body_size 10M; // укажите значение, которое вам нужно</pre>
<hr>
<b id="roles">Разделение ролей</b>
<p>Разделение ролей - очень простой в реализации момент. Для начала нужно добавить миграцию для создания поля role в таблице users.
После этого нужно добавить два статических поля ROLE_USER и ROLE_ADMIN, метод на изменение роли changeRole(), и метод isAdmin():</p>
<pre>
        // changeRole()
        public function changeRole($role): void
        {
            if (!\in_array($role, [self::ROLE_USER, self::ROLE_ADMIN], true)) {
                throw new \InvalidArgumentException('Undefined role "' . $role . '"');
            }
            if ($this->role === $role) {
                throw new \DomainException('Role is already assigned.');
            }
            $this->update(['role' => $role]);
        }
        // isAdmin()
        public function isAdmin(): bool
        {
            return $this->role === self::ROLE_ADMIN;
        }
</pre>
<p>Это в целом и есть разделение ролей, но тут есть три важных момента:</p>
<p><b>Момент 1</b> - поле role должно быть заполненным в миграции. И чтобы всем уже существующим пользователям проставилась роль, нужно сделать миграцию следующим образом:</p>
<pre>
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 16);
        });
        // Присвоить всем пользователям роль 'user'
        DB::table('users')->update([
        	'role' => 'user',
        ]);
    }
</pre>
<p><b>Момент 2</b> - плавно вытекает из первого, нам нужно проставить роль администратора некоторым пользователям, но как это сделать не редактируя пользователей напрямую в бд?
Для этого нам нужно сделать консольную команду - Console/Commands/User/RoleCommand.php, для этого мы можем опять использовать консольную команду:</p>
<pre>php artisan make:command "User\RoleCommand"</pre>
<p>После чего мы увидим файл, в котором находятся два поля $signature и $description, конструктор и метод handle().
Первое поле $signature описывает какая структура будет у команды. Поле $description выводит в консоли описание команды. В конструктор мы можем 
передать какие-нибудь сервисы, которые подтянет DI Container, но в нашем случае он не нужен. Последний метод handle() - обработчик команды, именно 
он и будет вызван при запросе. В нашем случае код команды будет выглядеть так:</p>
<pre>
    protected $signature = 'user:role {email} {role}';
    <br>
    protected $description = 'Set role for user';
    <br>
	public function handle()
    {
        // Получаем аргументы из строки запроса
		$email = $this->argument('email');
		$role = $this->argument('role');
        // Ищем пользователя по почте
		if (!$user = User::where('email', $email)->first()) {
			$this->error('Undefined user with email ' . $email);
			return false;
		}
        // Пробуем изменить его роль, и выдаём сообщение при успешном изменении
		try {
			$user->changeRole($role);
			$this->info('Role is successfully changed');
			return true;
		} catch (\DomainException $e) {
			$this->error($e->getMessage());
			return false;
		}
    }
</pre>
<p>Посмотреть, что наша команда находится в системе можно с помощью команды "php artisan list". И не забываем добавлять поле role в массив $fillable в классе User.</p>
<p><b>Момент 3 - </b> Нам нужно как-то дать доступ к определенным контроллерам только администраторам. 
Это относится к авторизации, и в Laravel решается при помощи специальной конструкции - <a href="https://laravel.com/docs/5.6/authorization#gates">Gate.</a></p>
<p>Мы можем прописать свой Gate в провайдере Providers/AuthServiceProvider.php в методе boot(), для этого нам нужно добавить в него свой код:</p>
<pre>
        Gate::define('admin', function (User $user) {
        	return $user->isAdmin();
        });
</pre>
<p>Теперь, для того, чтобы ограничить определенные контроллеры от юзеров, и дать доступ только администраторам - вы можете добавить посредника can:admin, например:</p>
<pre>$this->middleware('can:admin');</pre>
<hr>
<h3 id="books">Книги</h3>
<p>Следующий этап - сделать саму онлайн-библиотеку, для этого нам нужны сами книги. 
Начнём с модели, для начала нам нужно создать миграцию с определенными полями, которые её описывают, например:</p>
<b>Команда:</b>
<pre>
php artisan make:migration create_books_table --create=books
</pre>
<b>Миграция:</b>
<pre>
    public function up()
    {
        // Создаём таблицу авторов
        Schema::create('books_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status');
            $table->timestamps();
        });
        // Создаём таблицу жанров
        Schema::create('books_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status');
            $table->timestamps();
        });
        // Создаём таблицу книг
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('file_path');
            $table->string('status');
            $table->integer('genre_id')->references('id')->on('books_genres')->onDelete('RESTRICT');
            $table->integer('author_id')->references('id')->on('books_authors')->onDelete('RESTRICT');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });
    }
    // Удаляем все таблицы при откате
    public function down()
    {
        Schema::dropIfExists('books');
        Schema::dropIfExists('books_genres');
        Schema::dropIfExists('books_authors');
    }
</pre>

<p>После этого создайте модели жанра, автора и книги:</p>
<pre>
    php artisan make:model "Entities\Library\Book"
    php artisan make:model "Entities\Library\Book\Genre"
    php artisan make:model "Entities\Library\Book\Author"
</pre>
<p>На данном этапе просто заполняем статусы, пишем поля моделей, и делаем связи. Связи в laravel делаются следующим образом:</p>
<pre>
        //  Получить жанр по genre_id класса Book
        public function genre()
        {
            return $this->belongsTo(Genre::class, 'genre_id', 'id');
        }
        //  Получить автора по author_id класса Book
        public function author()
        {
            return $this->belongsTo(Author::class, 'author_id', 'id');
        }
        // Получить пользователя по user_id
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id', 'id');
        }
</pre>
<p>Связи belongsTo() позволяют найти один объект со связью один-к-одному. Если же вы хотите найти связь один-ко-многим, используйте функцию hasMany():</p>
<pre>
    public function books()
    {
        // Поиск книг, которые находятся в текущем жанре/авторе
        return $this->hasMany(Book::class, 'author_id', 'id');
    }
</pre>
<p>Потом нужно создать контроллер для книг в личном кабинете, и сделать контроллер для публичной части.
Также нужно сделать дополнительный Gate для того, чтоб пользователь мог работать только со своими книгами в личном кабинете. После этого 
нужно написать свои шаблоны для представления, и добавить пути в файл web.php.</p>
<hr>
<b id="reviews">Система рецензий для книг</b>
<p>Для реализации данного функционала нужно создать миграцию, в которой сделать связь с таблицей books и связь с таблицей users:</p>
<pre>
            Schema::create('books_reviews', function (Blueprint $table) {
                $table->increments('id');
                $table->string('review');
                $table->string('status');
                $table->integer('stars');
                $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
                $table->integer('book_id')->references('id')->on('books')->onDelete('CASCADE');
                $table->timestamps();
            });
</pre>
<p>После этого создаём контроллер для создания отзыва. После этого нужно не забыть сделать связь в классе Book на сами отзывы Review.
После этого делаем форму на странице просмотра книги с отзывами. На этом система рецензий завершена, останется только посчитать среднее арифметическое от всех отзывов для книги.</p>
<hr>
<b id="bundles">Budnle для книг, с возможностью группировки.</b>
<p>Для того, чтоб сделать бандл для книг, нам нужна сущность бандла, которая может содержать в себе книги по связи многие-ко-многим. Для этого нужно 
начать с миграции, в которой создать таблицу для самих бандлов, и связующую таблицу books_tables_assignments:</p>
<pre>
        // Создаём таблицу для бандлов
        Schema::create('books_bundles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });
        // Создаём связующую таблицу
        Schema::create('books_bundles_assignments', function (Blueprint $table) {
            $table->integer('bundle_id')->references('id')->on('books_bundle')->onDelete('CASCADE');
            $table->integer('book_id')->references('id')->on('books')->onDelete('CASCADE');
            $table->primary(['bundle_id', 'book_id']);
        });
</pre>
<p>После этого по привычной схеме настраиваем контроллер в кабинете, переписываем шаблоны под новую сущность и обновляем web.php. 
После этого появляется вопрос, как производится связь бандла с книгами через связующую таблицу? -Данный вопрос решается с помощью метода belongsToMany, и выглядит он 
следующим образом:</p>
<pre>
        public function books()
        {
            return $this->belongsToMany(Book::class, 'books_bundles_assignments', 'bundle_id', 'book_id');
        }
</pre>
<p>На базе данной связи можно сделать и добавление\удаление книг из бандла следующим образом:</p>
<pre>
        // Добавляем в бандл книгу по ID
        public function addToBundle($id): void
        {
            if ($this->hasInBundle($id)) {
                throw new \DomainException('This book is already added to bundle.');
            }
            $this->books()->attach($id);
        }
        // Удаляем книгу из бандла по ID
        public function removeFromBundle($id): void
        {
            $this->books()->detach($id);
        }
</pre>
<p>После этого останется только создать контроллер в публичной части, и вывести там наши бандлы.</p>
<hr>
<b id="books_view">Просмотр книг, с поиском и фильтрацией.</b>
<p>На данном этапе мы для вывода в публичной части уже подключили контроллер - Library\BookController, и контроллер для бандлов - 
Library\BundleController. Со своими шаблонами для вывода. Но на странице просмотра книг нет фильтра и поиска. Эта проблема решается с помощью 
формы с параметрами фильтров в шаблоне. После этого можно провалидировать данные в своём SearchRequest, или прямо в контроллере BookController.
</p>
<p>После этого нужно как-то сделать фильтрацию на стороне контроллера. Сделаем это с помощью примитивных проверок:</p>
<pre>
            $query = Book::orderByDesc('id');
            // Если есть строка поиска, добавляем её к поиску
            if (!empty($value = $request->get('search'))) {
                $query->where('title', 'like', '%' . $value . '%');
            }
            // Проделываем тоже самое с жанром
            if (!empty($value = $request->get('genre'))) {
                $query->where('genre_id', $value);
            }
            // Проделываем тоже самое с автором
            if (!empty($value = $request->get('author'))) {
                $query->where('author_id', $value);
            }
            // Выбираем книги с пагинацией равной 20 элементам на странице
            $books = $query->paginate(20);
</pre>
<p>Но при этом писать такой код в шаблоне является плохой практикой, поэтому желательно вынести фильтрацию и поиск в свой сервис - SearchService.
Такой подход является более правильным, мы сделаем данную реализацию, когда будем делать поиск с использованием ElasticSearch.
</p>
<p>После этого нужно передать жанры и авторов в шаблон, для этого используем обычную конструкцию:</p>
<pre>
        $genres = Genre::all();
        $authors = Author::all();
</pre>
<p>При этом нужно учитывать, что нам нужно передавать только авторов и жанры которые со статусом Active. Но пока нет админ-панели 
выводим все жанры и всех авторов. После создания админ-панели можно сделать специальные скоупы для жанра и автора -
scopeActive(), и использовать его:</p>
<pre>
    $genres = Genre::active()->get();
    $authors = Author::active()->get();
</pre>
<hr>
<b id="independent_writers">Реализация площадки для независимых писателей, с возможностью подписки и поддержки.</b>
<p>Для данного функционала можно создать ещё одну роль для пользователей, и назвать её writer. Но такой подход не является правильным, так
как в таком случае например администратор не сможет являться писателем. Поэтому мы сделаем миграцию, в которой добавляем поле is_writer в таблицу Users и создаём 
таблицу writers_subscribers, в которой делаем связь writer_id и subscriber_id, которые имеют внешний ключ на id в таблице users:</p>
<pre>
        public function up()
        {
            Schema::table('users', function (Blueprint $table) {
               $table->smallInteger('is_writer');
            });
            Schema::create('writers_subscribers', function (Blueprint $table) {
                $table->integer('writer_id')->references('id')->on('users')->onDelete('CASCADE');
                $table->integer('subscriber_id')->references('id')->on('users')->onDelete('CASCADE');
                $table->primary(['writer_id', 'subscriber_id']);
            });
        }
        public function down()
        {
            Schema::table('users', function(Blueprint $table) {
               $table->dropColumn('is_writer');
            });
            Schema::dropIfExists('writers_subscribes');
        }
</pre>
<p>После этого нам нужно сделать связь с данной таблицей в классе User:</p>
<pre>
        // Подписываем пользователя
        public function subscribe(int $id)
        {
            $this->defendWriter();
            if ($this->hasInSubscribers($id)) {
                throw new \DomainException('This user is already signed up');
            }
            $this->subscribers()->attach($id);
        }
        // Отписываем пользователя
        public function unsubscribe(int $id)
        {
            $this->defendWriter();
            $this->subscribers()->detach($id);
        }
        // Проверяем есть ли в подписчиках
        public function hasInSubscribers(int $id)
        {
            $this->defendWriter();
            return $this->subscribers()->where('id', $id)->exists();
        }
        // Связь с таблицей writers_subscribers
        public function subscribers()
        {
            $this->defendWriter();
            return $this->belongsToMany(self::class, 'writers_subscribers', 'writer_id', 'subscriber_id');
        }
        // Выбрасываем исключение, если пользователь не является писателем.
        public function defendWriter()
        {
            if (!$this->isWriter()) {
                throw new \DomainException('This user is not writer');
            }
        }
</pre>
<p>Также тут появляется такой момент, который часто встречается в приложениях - поле is_writer имеет тип smallint, но в приложении 
нам нужно сделать так, чтобы поле is_writer было типа boolean. Для этого в laravel, в любой модели есть переменная $casts, в которой
нужно следующим образом определить там переменную is_writer:</p>
<pre>
        protected $casts = [
            'is_writer' => 'boolean',
        ];
</pre>
<p>После этого останется только определить контроллер в публичной части для просмотра страницы пользователя, сделать там форму 
с помощью которой отправлять запрос на сервер для того, чтобы подписаться или отписаться от пользователя. И добавить два метода в Cabinet\ProfileController
для того чтобы можно было стать автором, или перестать быть автором. После этого нужно определить пути в файле routes/web.php.
На этом данный модуль будет готов.</p>
<p><b>Note:</b> Эта реализация подразумевает систему для независимых писателей, на которых можно подписаться. Для того, чтобы например 
осведомлять подписчиков о новой книге или бандле, вы можете сделать своё событие на базе этой системе. Например вы можете взять всех подписчиков при добавлении 
книги, и отправить им сообщение на почту. Подробнее работу с событиями я рассмотрю в дополнительных моментах.</p>
<h3 id="admin-panel">Админ-панель</h3>
<p>Для реализации админ-панели мы уже подготовили нужный Gate, когда делали роли для пользователей. Для реализации админ-панели 
нам нужно сделать новые контроллеры в админ-панели для модерирования пользователей, жанров, авторов, книг и бандлов.</p>
<b id="admin-genres-authors">Добавление жанров и писателей для книг</b>
<p>Для начала разберем работу с жанрами и авторами. Это два аналогичных класса, для которых действия будут очень похожими. Перед началом создадим 
контроллеры для админ-панели:</p>
<pre>
    php artisan make:controller -m"Entities\Library\Book\Author" "Admin\AuthorController"
    php artisan make:controller -m"Entities\Library\Book\Genre" "Admin\GenreController"
</pre>
<p>На этом моменте можно добавить в классы Author и Genre методы для работы со статусом, и scope - scopeActive(). И заменить вывод 
всех жанров и авторов в публичной части на вывод только активных.</p>
<p>После этого заполняем контроллеры, шаблоны вывода, и добавляем пути в routes/web.php:</p>
<pre>
    Route::group(
      [
          'prefix' => 'admin',
          'as' => 'admin.',
          'namespace' => 'Admin',
          'middleware' => ['auth', 'can:admin'], // Закрываем контроллеры от обычных пользователей
      ],
      function () {
            // Пути для авторов
            Route::resource('authors', 'AuthorController'); // Метод, с помощью которого laravel самостоятельно распарсит стандартные методы контроллера
            Route::post('/authors/{author}/set_active', 'AuthorController@setActive')->name('authors.set-active');
            Route::post('/authors/{author}/set_inactive', 'AuthorController@setInactive')->name('authors.set-inactive');
            // Пути для жанров
            Route::resource('genres', 'GenreController'); // Метод, с помощью которого laravel самостоятельно распарсит стандартные методы контроллера
            Route::post('/genres/{genre}/set_active', 'GenreController@setActive')->name('genres.set-active');
            Route::post('/genres/{genre}/set_inactive', 'GenreController@setInactive')->name('genres.set-inactive');
      }
    );
</pre>
<p>На этом модерацию жанров и авторов можно считать завершенной.</p>
<hr>
<b id="admin-users">Просмотр и модерация пользователей</b>
<p>Администрирование пользователей реализуется аналогично жанрам и авторам. Для начала нужно создать контроллер пользователя, и определить пути в 
файле routes/web.php, после этого написать реализацию методов контроллера, и сделать таблицу в шаблоне вывода. Из экзотических моментов 
мы должны сделать только дополнительный статический метод new() в классе User, для разделения создания юзера из публичной части, и через админ-панель.</p>
<hr>
<b id="admin-books">Просмотр и модерация всех книг с фильтрацией и поиском</b>
<p>Администрирование книг не отличается от администрирования других моделей. Мы создаём такой же контроллер, заполняем его данными, и добавляем пути в 
routes/web.php. Но книг у нас на сайте будет много, поэтому нужно сделать поиск по статусу, жанру, автору, заголовку и ID. Для этого мы должны добавить форму 
в шаблон вывода главной страницы администрирования книг, в которой разместить несколько инпутов, и кнопку поиска. А также в методе вывода страницы книг мы должны принять 
реквест, и взять из него параметры, которые нам пришли из этой формы:</p>
<pre>
    public function index(Request $request)
        {
            $query = Book::orderByDesc('id');
            // Если есть ID в реквесте, добавляем его к нашему запросу
            if (!empty($value = $request->get('id'))) {
                $query->where('id', $value);
            }
            // Если есть title в реквесте, добавляем его к нашему запросу
            if (!empty($value = $request->get('title'))) {
                $query->where('title', 'like', '%' . $value . '%');
            }
            //  Если есть author_id в реквесте, добавляем его к нашему запросу
            if (!empty($value = $request->get('author_id'))) {
                $query->where('author_id', $value);
            }
            //  Если есть genre_id в реквесте, добавляем его к нашему запросу
            if (!empty($value = $request->get('genre_id'))) {
                $query->where('genre_id', $value);
            }
            //  Если есть status в реквесте, добавляем его к нашему запросу
            if (!empty($value = $request->get('status'))) {
                $query->where('status', $value);
            }
            //  Получаем книги по нашему построенному запросу
            $books = $query->get();
            // Берем все статусы книг
            $statuses = [
                Book::STATUS_ACTIVE,
                Book::STATUS_WAIT,
                Book::STATUS_CANCELED,
            ];
            //  Получаем авторов и жанры для передачи в шаблон
            $authors = Author::all();
            $genres = Genre::all();
            //  Отображаем страницу с фильтрацией
            return view('admin.books.home', compact('books', 'statuses', 'authors', 'genres'));
        }
</pre>
<p>И помимо этого нам нужно сделать так, чтобы при активации книги мы могли сделать автоматически активными автора и жанр. Для 
этого немного изменим метод для перевода книги в активное состояние:</p>
<pre>
        public function setActive(Book $book)
        {
            try {
                $book->setActive();
                if (!$book->author->isActive()) {
                    $book->author->setActive();
                }
                if (!$book->genre->isActive()) {
                    $book->genre->setActive();
                }
            } catch (\DomainException $e) {
                return redirect()->route('admin.books.show', $book)->with('error', $e->getMessage());
            }
            return redirect()->route('admin.books.show', $book)->with('success', 'Success! This book is active!');
        }
</pre>
<p>На этом админ-панель завершена. Если вам нужно сделать администрирование бандлов, или отзывов, то вы можете сделать это по аналогии с прошлыми моделями.</p>
<hr>
<h3 id="additionals">Дополнительные моменты</h3>
<b id="breadcrumbs">Хлебные крошки</b>
<p>Хлебные крошки в laravel можно организовать разными способами, например можно в каждом шаблоне написать секцию 
breadcrumbs, и добавить её в главный layout:</p>
<pre>
    Секция в шаблоне:
        @section('breadcrumbs')
            // Ваша реализация хлебных крошек
        @endsection        
</pre>
<pre>
    Вывод в layout:
        @yield('breadcrumbs')
</pre>
<p>Но в данном подходе нам придётся всегда следить за родителями, и выписывать большие секции в каждый темплей. Поэтому мы 
сделаем другую реализацию хлебных крошек, с помощью специальной  библиотеки.</p>
<p>Мы уже добавили нужную библиотеку на этапе установки приложения, но на всякий случай проверьте ещё раз, что у вас подключена следующая библиотека:</p>
<pre>
    composer require davejamesmiller/laravel-breadcrumbs:5.x
</pre>
<p>После её установки, нам нужно проделать следующие действия:</p>
<p><b>Шаг первый:</b> для начала нам нужно добавить вывод хлебных крошек в layout-e, для этого пропишем в нём следующее:</p>
<pre>
                    @section('breadcrumbs', Breadcrumbs::render())
                    @yield('breadcrumbs')
</pre>
<p><b>Шаг второй:</b> на данном этапе если вы перейдёте на любую из страниц, приложение будет ругаться, что хлебные крошки 
для данного пути не найдены. Поэтому нам нужно создать файл routes/breadcrumbs.php, и прописать для каждого пути следующую конструкцию:</p>
<pre>
    // Путь для главной страницы нашего приложения
    Breadcrumbs::register('library.books.home', function (BreadcrumbsGenerator $crumbs) {
        $crumbs->push('Home', route('library.books.home'));
    });
    // Добавляем крошку, наследуясь от главной страницы, таким образом получим крошки Home/Admin
    Breadcrumbs::register('admin.home', function (BreadcrumbsGenerator $crumbs) {
        $crumbs->parent('library.books.home');
        $crumbs->push('Admin', route('admin.home'));
    });
    // Проделываем тоже самое с главной страницей книг в админке, получим - Home/Admin/Books
    Breadcrumbs::register('admin.books.index', function (BreadcrumbsGenerator $crumbs) {
       $crumbs->parent('admin.home');
       $crumbs->push('Books', route('admin.books.index'));
    });
    // Делаем тоже самое со страницей просмотра, и передаём ещё один нужный параметр - книгу $book
    // Строим крошки, получим - Home/Admin/Books/*Название книги*
    Breadcrumbs::register('admin.books.show', function (BreadcrumbsGenerator $crumbs, Book $book) {
        $crumbs->parent('admin.books.index');
        $crumbs->push($book->title, route('admin.books.show', $book));
    });
    // Повторяем действие для изменения, и получим - Home/Admin/Books/*Название книги*/Edit
    Breadcrumbs::register('admin.books.edit', function (BreadcrumbsGenerator $crumbs, Book $book) {
        $crumbs->parent('admin.books.show', $book);
        $crumbs->push('Edit', route('admin.books.edit', $book));
    });
    // Проделываем аналогично для страницы создания книги
    Breadcrumbs::register('admin.books.create', function (BreadcrumbsGenerator $crumbs) {
        $crumbs->parent('admin.books.index');
        $crumbs->push('Create book', route('admin.books.create'));
    });
</pre>
<p>Теперь нам нужно сделать таким образом хлебные крошки для каждой страницы. Если вы хотите проверить какие страницы нужно покрыть хлебными крошками,
то вы можете посмотреть все названия путей с помощью команды:</p>
<pre>
    php artisan route:list
    // Или сокращенная запись + только пути, которые работают по GET-запросу.
    php artisan ro:li --method=get
</pre>
<p><b>Шаг третий:</b> теперь нам осталось только убрать хлебные крошки на главной странице. Для этого вам нужно просто добавить в шаблоне главной страницы
секцию breadcrumbs, и оставить её пустой, в таком случае выведется именно она.</p>
<hr>
<b id="elasticsearch">Усовершенствованный поиск с использованием ElasticSearch</b>
<p>Для поиска с помощью elasticsearch, добавим сначала elasticsearch в docker. Для этого добавим в docker-compose следующие строки:</p>
<pre>
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:6.2.4
    environment:
      - bootstrap.memory_lock=true
      - "ES_JAVA_OPTS=-Xms128m -Xmx128m"
    ulimits:
      memlock:
        soft: -1
        hard: -1
    volumes:
      - ./storage/docker/elasticsearch:/usr/share/elasticsearch/data
    ports:
      - "9201:9200"
</pre>
<p>После этого перед запуском docker-compose up --build, мы должны увеличить память для нашей виртуальной машины, для этого выполните 
команду в консоли:</p>
<pre>sudo sysctl -w vm.max_map_count=262144</pre>
<p>После этого нужно добавить линк на elasticsearch в php-fpm и php-cli, и можно запускать docker-compose с флагом --build.</p>
<p>Теперь добавьте в .env файл строчку:</p>
<pre>ELASTICSEARCH_HOSTS=127.0.0.1:9201</pre>
<p>И добавим файл config/elasticsearch.php следующего вида:</p>
<pre>
return [
    'hosts' => explode(',', env('ELASTICSEARCH_HOSTS')),
    'retries' => 1,
];
</pre>
<p>Теперь можно создать провайдер SearchServiceProvider, и добавить его в config/app.php к остальным провайдерам.
Сам же провайдер должен быть следующим:</p>
<pre>
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
class SearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function(Application $app) {
            $config = $app->make('config')->get('elasticsearch'); //  config/elasticsearch.php
            return ClientBuilder::create()
                ->setHosts($config['hosts'])    // Задаём хост 127.0.0.1:9201
                ->setRetries($config['retries'])
                ->build();
        });
    }
}
</pre>
<p>Следующий шаг - создание двух консольных команд InitCommand и ReindexCommand:</p>
<pre>php artisan make:command "Search\InitCommand"</pre>
<pre>php artisan make:command "Search\ReindexCommand"</pre>
<p>В InitCommand нужно принять в конструкторе клиент Elasticsearch\Client, который подтянется через контейнер внедрения зависимостей, а
определен он в провайдере, который мы написали раньше. И напишем следующий обработчик:</p>
<pre>
        try {
            $this->client->indices()->delete([ // Удаляем индекс с названием app.
                'index' => 'app',
            ]);
        } catch (Missing404Exception $e) {}
        $this->client->indices()->create([      // Создаём индекс именем app
            'index' => 'app',                   // Название индекса
            'body' => [                         // Тело индекса
                'mappings' => [                 // Карта сущностей
                    'book' => [                 // Название сущности
                        '_source' => [          // Включение этого параметра сохранит весь документ JSON в индексе, и при необходимости можно вернуть только определенные поля.
                            'enabled' => true,
                        ],
                        'properties' => [       // Свойства
                            'id' => [
                                'type' => 'integer',
                            ],
                            'published_at' => [
                                'type' => 'date',
                            ],
                            'title' => [
                                'type' => 'text',
                            ],
                            'description' => [
                                'type' => 'text',
                            ],
                            'genre' => [
                                'type' => 'integer',
                            ],
                            'author' => [
                                'type' => 'integer',
                            ],
                            'status' => [
                                'type' => 'keyword',  // Тип keyword означает строку-ключ, поиск по данному полю не будет производится.
                            ],
                        ],
                    ],
                ],                      // Настройки для поиска
                'settings' => [
                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '&=> and '
                                ],
                            ],
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ],
                            'trigrams' => [
                                'type' => 'ngram',
                                'min_gram' => 4,
                                'max_gram' => 6,
                            ],
                        ],
                        'analyzer' => [
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace',
                                ],
                                'tokenizer' => 'whitespace',
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter',
                                    'trigrams',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
</pre>
<p>Это позволит нам создать нужные настройки поиска, и поля для индексации. После этого нам нужно проиндексировать наши книги. 
Для этого у нас будет следующая команда - ReindexCommand. В ней мы возьмём все активные книги, и проиндексируем их с помощью следующей функции:</p>
<pre>
            $this->client->index([  // Elasticsearch\Client
                'index' => 'app',
                'type' => 'book',
                'id' => $book->id,
                'body' => [
                    'id' => $book->id,
                    'published_at' => $book->published_at ? $book->published_at->format(DATE_ATOM) : null,
                    'title' => $book->title,
                    'description' => $book->description,
                    'status' => $book->status,
                    'genre' => $book->genre_id,
                    'author' => $book->author_id,
                ],
            ]);
</pre>
<p>Данную функцию можно вынести в отдельный сервис BookIndexer.</p>
<p>Теперь нам осталось изменить наш контроллер, и заменить наш поиск на поиск с помощью elasticsearch:</p>
<pre>
    $books = $this->search->search($request, 20, $request->get('page', 1));
</pre>
<p>Где search - это сервис SearchService, который содержит следующую функцию search:</p>
<pre>
    public function search(Request $request, int $perPage, int $page): Paginator  // Illuminate\Contracts\Pagination\Paginator
        {
            $author = null;
            $genre = null;
            // Если в реквесте есть автор, получаем его
            if ($request['author']) {
                $author = Author::findOrFail($request['author']);
            }
            // Если в реквесте есть жанр, получаем его
            if ($request['genre']) {
                $genre = Genre::findOrFail($request['genre']);
            }
            // Получаем ответ от elasticsearch, передавая в неё наш запрос
            $response = $this->client->search([
                'index' => 'app',
                'type' => 'book',
                'body' => [
                    '_source' => ['id'],    // Поле, которое мы хотим получить в ответе.
                    'from' => ($page - 1) * $perPage,
                    'size' => $perPage,
                    'sort' => [],
                    'query' => [    // Конфигурация нашего запроса в зависимости от реквеста
                        'bool' => [
                            'must' => array_merge(
                                [
                                    ['term' => ['status' => Book::STATUS_ACTIVE]]
                                ],
                                array_filter([
                                    $author ? ['term' => ['author' => $author->id]] : false,
                                    $genre ? ['term' => ['genre' => $genre->id]] : false,
                                    !empty($request['search']) ? ['multi_match' => [
                                        'query' => $request['search'],
                                        'fields' => [ 'title^3', 'description' ],
                                    ]] : false,
                                ])
                            ),
                        ]
                    ],
                ],
            ]);
            // Получаем наши пересечения
            $ids = array_column($response['hits']['hits'], '_id');
            // Если ничего не нашли, возвращаем пустой Paginator
            if (!$ids) {
                return new LengthAwarePaginator([], 0, $perPage, $page);
            }
            // Если нашли пересечения, то выбираем их.
            $items = Book::active()
                ->with(['author', 'genre'])
                ->whereIn('id', $ids)
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
                ->get();
            // Возвращаем заполненный Paginator
            return new LengthAwarePaginator($items, $response['hits']['total'], $perPage, $page);
        }
</pre>
<p>Таким образом мы организовали поиск с помощью elasticsearch.</p>
<hr>
<b id="appeals">Обращения к модераторам, блокировка нежелательного контента</b>
<p>Для реализации данного функционала нам нужно сделать новый метод для добавления запросов на модерацию контента в публичной части, вывод 
в адми-панели информации о таких запросах, и возможность их паринятия или отклонения.</p>
<hr>
<b id="pagination">Пагинация</b>
<p>Для создания пагинации в Laravel, вы можете пойти несколькими путями, самый простой путь - вызвать функцию paginate у
построителя запросов следующим образом:</p>
<pre>$books = Book::forUser($user)->orderByDesc('id')->paginate(20);</pre>
<p>Или же вы можете вручную создать пагинатор следующим способом:</p>
<pre>
    new LengthAwarePaginator($books->forPage($page, $perPage), $books->count(), $perPage, $page);
</pre>
<p>Для вывода пагинации на странице вызовите следующий метод пагинатора:</p>
<pre>{{ $books->links() }}</pre>
<hr>
<b id="events">Оповещение пользователей о новой книге/бандле от независимого разработчика</b>
<p>Для реализации данного функционала нам нужно сконфигурировать отправку сообщений через SMTP, для этого 
нужно добавить параметры для отправления почты в .env файле. Функционал для отправки сообщений уже встроен в Laravel, поэтому 
для отправки сообщений вам необязательно использовать свой сервис. Однако система нотификации в laravel позволяет расширять свой фукнционал, 
и добавлять дополнительные сервисы для нотификации. Например СМС-сервис.</p>
<p>Затем нам нужно понять, как же происходит отправка уведомлений, и как вообще работать с событиями. В Laravel есть два типа 
классов для работы с событиями - Сами события, и слушатели, которые подписываются на события.</p>
<p>Для создания события воспользуйтесь следующей командой:</p>
<pre>php artisan make:event "EventName"</pre>
<p>А для создания слушателя используйте следующую команду:</p>
<pre>php artisan make:listener "ListenerName"</pre>
<p>После этого вам нужно в методе handle(), в слушаетеле сделать какую-то обработку, например:</p>
<pre>
    public function handle(BookCreated $event)
    {
        $book = $event->book;
        foreach ($book->user->subscribers as $subscriber) {
            $subscriber->notify(new BookCreatedNotification($book));
        }
    }
</pre>
<p>И теперь вам останется подписать слушателя на событие, и вызвать событие. Для этого в провайдере EventServiceProvider нужно добавить в массиве $listen следующие строчки:</p>
<pre>
            Event::class => [
                Listener::class,
            ],
</pre>
<p>А для вызова события воспользуйтесь вызовом следующего метода в любой части кода:</p>
<pre>event(new EventName($args));</pre>
<hr>
<h3 id="api">API</h3>
<p>Часто у больших сайтов возникает потребность в построении внешнего API. Например если вы хотите сделать мобильное приложение 
для своего продукта, и вам нужно загружать информацию с сайта для этого приложения. В данном случае мы разберемся как сделать 
такое API (REST API\JSON API) в Laravel для нашего сайта.</p>

<b>Регистрация и логин</b>
<p>Основа работы с API - Аутентификация. И сейчас мы разберемся как зарегистрироваться используя наше API.</p>
<p>Для начала создадим новый контроллер в папке Api для регистрации пользователя:</p>
<pre>php artsian make:controller "Api\Auth\RegisterController"</pre>
<p>После чего добавляем в контроллер следующий метод:</p>
<pre>
    public function register(RegisterRequest $request)
    {
        $this->service->register($request); // RegisterService
        return response()->json([
                'success' => 'Check your email and click on the link to verify.',
        ], Response::HTTP_CREATED);
    }
</pre>
<p>Теперь нам нужно прописать путь, по которому будет производиться обращение к нашему контроллеру. И вот тут мы сталкиваемся с 
таким отличием API от обычной части сайта, которая выводится в браузере, что нам при обращении к API методам не нужно большого количества 
функционала, например нам не нужны сессии, куки и т.д. В случае с API нам нужно как можно более быстро отдать ответ на полученный запрос. 
И в Laravel это уже предусмотрено, и для Api маршрутов применяются другие правила и подходы. И для указания таких маршрутов, их нужно 
 записывать в файле routes/api.php:</p>
 <pre>Route::post('/register', 'Api\RegisterController@register');</pre>
<p>На этом регистрация закончена, вам нужно лишь передать поля name, email, password в формате JSON методом POST по пути /api/register. Но для того, чтобы 
сайт принял ваши данные в формате JSON, передайте заголовок Content-Type: application/json. Тоже самое и для заголовка, который указывает 
какой ответ вы ожидаете - Accept: application/json.</p>

<hr>
<p>Следующая часть нашего API - аутентификация на сайте. И для её реализации Laravel предоставляет нам следующий инструмент - 
Laravel Passport. Установим его через composer: </p>
<pre>composer require laravel/passport</pre>
<p>Подбробнее о данном пакете вы можете почитать в <a href="https://laravel.com/docs/5.6/passport">официальной документации Laravel</a>.</p>
<p>После установки данного пакета, нам нужно либо вручную перенести миграции из папки vendor/laravel/passport/database в папку наших миграций, либо 
перенести их командой</p>
<pre>php artisan vendor:publish --tag=passport-migrations</pre>
<p>И после этого применить новые миграции, предварительно прописав в методе register провайдера AppServiceProvider следующую строчку: Passport::ignoreMigrations();.</p>
<p>Теперь выполним следующую команду для работы с пакетом:</p>
<pre>php artisan passport:install</pre>
<p>И нам останется лишь добавить трейт Laravel\Passport\HasApiTokens в наш класс User. Теперь мы можем войти в систему следующим способом -
нам нужно отправить следующие данные по адресу /oauth/token методом POST:</p>
<pre>
{
    "grant_type": "password",
    "client_id": 2,
    "client_secret": "3f17eSN7PE7pLR67rwC4ug5T3bn0NOb5Ts6Qizum",
    "username": "qposer@gmail.com",
    "password": "secret1",
    "scope": ""
}
</pre>
<p>В данном контексте используйте client_id и client_secret - это данные, которые были выведены в консоли после выполнения команды passport:install, например у меня они были такие:</p>
<pre>
    Encryption keys generated successfully.
    Personal access client created successfully.
    Client ID: 1
    Client Secret: HaguiVYDL66i7HGRmiWw1KsnAYCSHIZLt7H60Sn2
    Password grant client created successfully.
    Client ID: 2
    Client Secret: 3f17eSN7PE7pLR67rwC4ug5T3bn0NOb5Ts6Qizum
</pre>
<p>В данном случае первый клиент отвечает за выпуск персональных токенов, а ко второму клиенту мы обращаемся для аутентификации по логину и паролю. 
На этом этап аутентификации завершен. После обращения к /oauth/token при успешном обращении вы получите два токена - access_token и refresh token:</p>
<pre>
{"token_type":"Bearer",
"expires_in":31536000,
"access_token":"Длинный access_token",
"refresh_token":"Не очень длинный refresh_token"}
</pre>
<p>И теперь для доступа по токену - используйте access_token. Для этого передайте ещё один заголовок при запросе: Authorization: Bearer *access_token*.</p>
<p>В свою очередь refresh_token нужен для того, чтобы перегенирировать access_token при его истечении.</p>
<b>Своя сериализация сущностей</b>
<p>Для того, чтобы самому контролировать какие поля вы хотите выдать в ответе, нужно возвращать не саму модель, а преобразованный массив. 
Для этой задачи в Laravel есть специальная сущность - Resource. Например для профиля мы можем создать ресурс следующей командой:</p>
<pre>php artisan make:resource "User\ProfileResource"</pre>
<p>И после этого в методе toArray написать подобный код:</p>
<pre>
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'email' => $this->email,
                'name' => $this->name,
                'books' => array_map(function (array $book) {
                    return [
                        'id' => $book['id'],
                        'title' => $book['title'],
                    ];
                }, $this->books->toArray()),
            ];
        }
</pre>
<p>Теперь осталось только вернуть в контроллере данный ресурс следующим образом:</p>
<pre>return new ProfileResource($request->user());</pre>






