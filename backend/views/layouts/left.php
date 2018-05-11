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
