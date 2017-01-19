# Getting started with Yii2-plugins-system
[![Latest Stable Version](https://poser.pugx.org/loveorigami/yii2-plugins-system/v/stable)](https://packagist.org/packages/loveorigami/yii2-plugins-system) 
[![Total Downloads](https://poser.pugx.org/loveorigami/yii2-plugins-system/downloads)](https://packagist.org/packages/loveorigami/yii2-plugins-system)
[![License](https://poser.pugx.org/loveorigami/yii2-plugins-system/license)](https://packagist.org/packages/loveorigami/yii2-plugins-system)

Yii2-plugins-system is designed to work out of the box. It means that installation requires
minimal steps. Only one configuration step should be taken and you are ready to
have plugin system on your Yii2 website.

!["Plugins"](docs/img/tab_plugins.jpg)

### 1. Download

Yii2-plugins-system can be installed using composer. Run following command to download and
install Yii2-plugins-system:

```bash
composer require "loveorigami/yii2-plugins-system": ">=3.*"
```

### 2. Update database schema

The last thing you need to do is updating your database schema by applying the
migrations. Make sure that you have properly configured `db` application component
and run the following command:

```bash
$ php yii migrate/up --migrationPath=@vendor/loveorigami/yii2-plugins-system/migrations
```

### 3. Configure application

Let's start with defining module in `@backend/config/main.php`:

```php
'modules' => [
    'plugins' => [
        'class' => 'lo\plugins\Module',
        'pluginsDir'=>[
            '@lo/plugins/core', // default dir with core plugins
            // '@common/plugins', // dir with our plugins
        ]
    ],
],
```
That's all, now you have module installed and configured in advanced template.

Next, open `@frontend/config/main.php` and add following:

```php
...
'components' => [
    'plugins' => [
        'class' => lo\plugins\components\PluginsManager::class,
        'appId' => 1 // lo\plugins\BasePlugin::APP_FRONTEND,
        // by default
        'enablePlugins' => true,
        'shortcodesParse' => true,
        'shortcodesIgnoreBlocks' => [
            '<pre[^>]*>' => '<\/pre>',
            //'<div class="content[^>]*>' => '<\/div>',
        ]
    ],
    'view' => [
        'class' => lo\plugins\components\View::class,
    ]
    ...
]
```

Also do the same thing with `@backend/config/main.php`:

```php
...
'components' => [
    'plugins' => [
        'class' => lo\plugins\components\PluginsManager::class,
        'appId' => 2 // lo\plugins\BasePlugin::APP_BACKEND
    ],
    'view' => [
        'class' => lo\plugins\components\View::class,
    ]
    ...
]
```

## Shortcodes
* [Yii2-shortcodes-pack](https://github.com/loveorigami/yii2-shortcodes-pack)

## Core plugins (examples)

* [Hello world!] (src/core/helloworld)
* [Http Authentication] (src/core/httpauth)

## Your plugins

* [Create] (docs/create_plugin.md)
* [Install] (docs/install_plugin.md)

## Contributing to this project

Anyone and everyone is welcome to contribute. Please take a moment to
review the [guidelines for contributing](CONTRIBUTING.md).

## License

Yii2-plugins-system is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.