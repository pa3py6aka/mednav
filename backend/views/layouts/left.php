<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Пользователи', 'icon' => 'users', 'active' => $this->context->id === 'user', 'items' => [
                        ['label' => 'Пользователи', 'icon' => 'users', 'url' => ['/user/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/user/settings']],
                    ]],
                    ['label' => 'Гео', 'icon' => 'globe', 'url' => ['/geo/index'], 'active' => $this->context->id === 'geo'],
                    ['label' => 'Доска объявлений', 'icon' => 'newspaper-o', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/board/category/index'], 'active' => $this->context->id === 'board/category'],
                        ['label' => 'Объявления', 'icon' => 'newspaper-o', 'url' => ['/board/board/active'], 'active' => $this->context->id === 'board/board'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/board/settings/main'], 'active' => $this->context->id === 'board/settings'],
                    ]],
                    ['label' => 'Каталог компаний', 'icon' => 'book', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/company/category/index'], 'active' => $this->context->id === 'company/category'],
                        ['label' => 'Компании', 'icon' => 'newspaper-o', 'url' => ['/company/company/active'], 'active' => $this->context->id === 'company/company'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/company/settings/main'], 'active' => $this->context->id === 'company/settings'],
                    ]],
                    ['label' => 'Каталог товаров', 'icon' => 'cubes', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/trade/category/index'], 'active' => $this->context->id === 'trade/category'],
                        ['label' => 'Категории', 'icon' => 'list', 'url' => ['/trade/user-category/index'], 'active' => $this->context->id === 'trade/user-category'],
                        ['label' => 'Товары', 'icon' => 'cubes', 'url' => ['/trade/trade/active'], 'active' => $this->context->id === 'trade/trade'],
                        ['label' => 'Заказы', 'icon' => 'sticky-note-o', 'url' => ['/trade/order/index'], 'active' => $this->context->id === 'trade/order'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/trade/settings/main'], 'active' => $this->context->id === 'trade/settings'],
                    ]],
                    ['label' => 'Статьи', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/article/category/index'], 'active' => $this->context->id === 'article/category'],
                        ['label' => 'Статьи', 'icon' => 'list-alt', 'url' => ['/article/article/active'], 'active' => $this->context->id === 'article/article'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/article/settings/main'], 'active' => $this->context->id === 'article/settings'],
                    ]],
                    ['label' => 'Новости', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/news/category/index'], 'active' => $this->context->id === 'news/category'],
                        ['label' => 'Новости', 'icon' => 'list-alt', 'url' => ['/news/news/active'], 'active' => $this->context->id === 'news/news'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/news/settings/main'], 'active' => $this->context->id === 'news/settings'],
                    ]],
                    ['label' => 'Новости компаний', 'icon' => 'tasks', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/cnews/category/index'], 'active' => $this->context->id === 'cnews/category'],
                        ['label' => 'Новости компаний', 'icon' => 'tasks', 'url' => ['/cnews/cnews/active'], 'active' => $this->context->id === 'cnews/cnews'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/cnews/settings/main'], 'active' => $this->context->id === 'cnews/settings'],
                    ]],
                    ['label' => 'Бренды', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/brand/category/index'], 'active' => $this->context->id === 'brand/category'],
                        ['label' => 'Бренды', 'icon' => 'list-alt', 'url' => ['/brand/brand/active'], 'active' => $this->context->id === 'brand/brand'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/brand/settings/main'], 'active' => $this->context->id === 'brand/settings'],
                    ]],
                    ['label' => 'Выставки', 'icon' => 'calendar', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/expo/category/index'], 'active' => $this->context->id === 'expo/category'],
                        ['label' => 'Выставки', 'icon' => 'calendar', 'url' => ['/expo/expo/active'], 'active' => $this->context->id === 'expo/expo'],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/expo/settings/main'], 'active' => $this->context->id === 'expo/settings'],
                    ]],
                    ['label' => 'Страницы', 'icon' => 'columns', 'items' => [
                        ['label' => 'ППУ', 'url' => ['/page/index'], 'active' => $this->context->id === 'page'],
                        ['label' => 'Фронт', 'url' => ['/front-page/index'], 'active' => $this->context->id === 'front-page'],
                    ]],

                    ['label' => 'Блоки', 'icon' => 'object-ungroup', 'items' => [
                        ['label' => 'Контентные блоки', 'url' => ['/blocks/contents/index'], 'active' => $this->context->id === 'blocks/contents'],
                        ['label' => 'Контекстные блоки', 'url' => ['/blocks/context/index'], 'active' => $this->context->id === 'blocks/context'],
                    ]],
                    [
                        'label' => 'Сообщения',
                        'url' => ['/dialog/dialogs'],
                        'icon' => 'envelope',
                        'template' => '<a href="{url}">{icon} {label}' . \core\helpers\DialogHelper::getSupportNewMessagesCount() . '</a>',
                        'active' => $this->context->id === 'dialog'
                    ],
                    ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/settings/index'], 'active' => $this->context->id === 'settings'],
                    [
                        'label' => 'Выйти',
                        'url' => ['/site/logout'],
                        'icon' => 'sign-out',
                        'visible' => !Yii::$app->user->isGuest,
                        'template' => '<a href="{url}" data-method="post">{icon} {label}</a>'
                    ],
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header'], 'visible' => YII_ENV_DEV],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'], 'visible' => YII_ENV_DEV],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'], 'visible' => YII_ENV_DEV],
                ],
            ]
        ) ?>

    </section>

</aside>
