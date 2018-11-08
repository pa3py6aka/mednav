<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Пользователи', 'icon' => 'users', 'items' => [
                        ['label' => 'Пользователи', 'icon' => 'users', 'url' => ['/user/index']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/user/settings']],
                    ]],
                    ['label' => 'Гео', 'icon' => 'globe', 'url' => ['/geo/index'], 'active' => $this->context->id == 'geo'],
                    ['label' => 'Доска объявлений', 'icon' => 'newspaper-o', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/board/category/index']],
                        ['label' => 'Объявления', 'icon' => 'newspaper-o', 'url' => ['/board/board/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/board/settings/main']],
                    ]],
                    ['label' => 'Каталог компаний', 'icon' => 'book', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/company/category/index']],
                        ['label' => 'Компании', 'icon' => 'newspaper-o', 'url' => ['/company/company/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/company/settings/main']],
                    ]],
                    ['label' => 'Каталог товаров', 'icon' => 'cubes', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/trade/category/index']],
                        ['label' => 'Товары', 'icon' => 'cubes', 'url' => ['/trade/trade/active']],
                        ['label' => 'Заказы', 'icon' => 'sticky-note-o', 'url' => ['/trade/order/index']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/trade/settings/main']],
                    ]],
                    ['label' => 'Статьи', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/article/category/index']],
                        ['label' => 'Статьи', 'icon' => 'list-alt', 'url' => ['/article/article/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/article/settings/main']],
                    ]],
                    ['label' => 'Новости', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/news/category/index']],
                        ['label' => 'Новости', 'icon' => 'list-alt', 'url' => ['/news/news/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/news/settings/main']],
                    ]],
                    ['label' => 'Новости компаний', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/cnews/category/index']],
                        ['label' => 'Новости компаний', 'icon' => 'list-alt', 'url' => ['/cnews/cnews/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/cnews/settings/main']],
                    ]],
                    ['label' => 'Бренды', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/brand/category/index']],
                        ['label' => 'Бренды', 'icon' => 'list-alt', 'url' => ['/brand/brand/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/brand/settings/main']],
                    ]],
                    ['label' => 'Выставки', 'icon' => 'list-alt', 'items' => [
                        ['label' => 'Разделы', 'icon' => 'list', 'url' => ['/expo/category/index']],
                        ['label' => 'Выставки', 'icon' => 'list-alt', 'url' => ['/expo/expo/active']],
                        ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/expo/settings/main']],
                    ]],
                    ['label' => 'Блоки', 'icon' => 'object-ungroup', 'items' => [
                        ['label' => 'Контентные блоки', 'url' => ['/blocks/contents/index']],
                        ['label' => 'Контекстные блоки', 'url' => ['/blocks/context/index']],
                    ]],
                    ['label' => 'Настройки', 'icon' => 'wrench', 'url' => ['/settings/index']],
                    [
                        'label' => 'Выйти',
                        'url' => ['site/logout'],
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
