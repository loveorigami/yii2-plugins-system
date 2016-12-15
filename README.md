# Getting started with Yii2-plugins-system

Yii2-plugins-system is designed to work out of the box. It means that installation requires
minimal steps. Only one configuration step should be taken and you are ready to
have plugin system on your Yii2 website.

!["Plugins"](docs/img/tab_plugins.jpg)

### 1. Download

Yii2-plugins-system can be installed using composer. Run following command to download and
install Yii2-plugins-system:

```bash
composer require "loveorigami/yii2-plugins-system": "2.0"
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
            '@lo/plugins/plugins', // default dir with core plugins
            // '@common/plugins', // dir with our plugins
        ]
    ],
],
```
That's all, now you have module installed and configured in advanced template.

Next, open `@frontend/config/main.php` and add following:

```
'bootstrap' => ['log', 'plugins'],
...
'components' => [
    'plugins' => [
        'class' => 'lo\plugins\components\EventBootstrap',
        'appId' => 'frontend'
    ],
    ...
]
```

Also do the same thing with `@backend/config/main.php`:

```
'bootstrap' => ['log', 'plugins'],
...
'components' => [
    'plugins' => [
        'class' => 'lo\plugins\components\EventBootstrap',
        'appId' => 'backend'
    ],
    ...
]
```

## Core plugins (examples)

* [Hello world!] (plugins/helloworld)
* [Code Highlighting] (plugins/code)
* [Http Authentication] (plugins/httpauth)

## Your plugins

* [Create] (docs/create_plugin.md)
* [Install] (docs/install_plugin.md)

## Contributing to this project

Anyone and everyone is welcome to contribute. Please take a moment to
review the [guidelines for contributing](CONTRIBUTING.md).

## License

Yii2-plugins-system is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.