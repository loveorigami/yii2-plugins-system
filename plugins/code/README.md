# Code Highlighting

### Config

After installiation in plugins system, you can change default config

```php
    [
        'style' => 'github',
        'lang' => 'php'
    ];
```

### Usage

All the blocks of text enclosed in the shortcode [code], will be highlighted.
For example:

```php
    [code] ... our text ... [/code]
```
or, if the language of highlighting is different from the by default `php`
 ```php
     [code lang="html"] ... our text ... [/code]
 ```

 ### Links

 * More langs and styles you can find in [https://highlightjs.org] (https://highlightjs.org)