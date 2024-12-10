![Filament Translatable](docs/images/banner.png)

# Filament Translatable 
### This package cloned from [outerweb-filament-translatable-fields](https://github.com/outer-web/filament-translatable-fields)

We are using this for our use cases.



This package adds a way to make all filament fields translatable.
It uses the `spatie/laravel-translatable` package in the background.

## Installation

First install and configure your model(s) to use the `spatie/laravel-translatable` package.

You can install the package via composer:

```bash
composer require go2stone/filament-translatable
```

Add the plugin to your desired Filament panel:

```php
use Go2Stone\FilamentTranslatable\Filament\Plugins\FilamentTranslatablePlugin;

class FilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugins([
                FilamentTranslatablePlugin::make(),
            ]);
    }
}
```

You can specify the supported locales:

```php
use Go2Stone\FilamentTranslatable\Filament\Plugins\FilamentTranslatablePlugin;

class FilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugins([
                FilamentTranslatablePlugin::make()
                    ->supportedLocales([
                        'en' => 'English',
                        'nl' => 'Dutch',
                    ]),
            ]);
    }
}
```

Also you can specify localized tab labels
PHP's locale_get_display_name() function to set the labels of the tabs to the display name of the active language.

```php
use Go2Stone\FilamentTranslatable\Filament\Plugins\FilamentTranslatablePlugin;

class FilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugins([
                FilamentTranslatablePlugin::make()
                    ->supportedLocales([
                        'en' => 'English',
                        'nl' => 'Dutch',
                    ])
                    ->localeTabLabels(true),
            ]);
    }
}
```


By default, the package will use the `app.locale` if you don't specify the locales.

### Combining with the official [spatie-laravel-translatable-plugin](https://github.com/filamentphp/spatie-laravel-translatable-plugin)?

This package is a replacement for the official on the **create** and **edit** pages only. If you are already using the official package, you will have to delete the `use Translatable` trait and the `LocaleSwitcher` header action from those pages:

```diff
-use Filament\Actions\LocaleSwitcher;
-use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditPage extends EditRecord
{
-    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
-            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
```

## Usage

You can simply add `->translatable()` to any field to make it translatable.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Name')
    ->translatable(),
```

## Disable translations dynamically

If you want to disable translations dynamically, you can set the first parameter of the `->translatable()` function to `true` or `false`.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Name')
    ->translatable(false),
```

## Overwrite locales

If you want to overwrite the locales on a specific field you can set the locales through the second parameter of the `->translatable()` function.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Name')
    ->translatable(true, ['en' => 'English', 'nl' => 'Dutch', 'fr' => 'French']),
```

## Locale specific validation rules

You can add locale specific validation rules with the third parameter of the `->translatable()` method.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Name')
    ->translatable(true, null, [
        'en' => ['required', 'string', 'max:255'],
        'nl' => ['nullable', 'string', 'max:255'],
    ]);
```

or 

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Name')
    ->translatable(localeSpecificRules: [
        'en' => ['required', 'string', 'max:255'],
        'nl' => ['nullable', 'string', 'max:255'],
    ]);
```

### Good to know

This package will substitute the original field with a `Filament\Forms\Components\Tabs` component. This component will render the original field for each locale.

All chained methods you add before calling `->translatable()` will be applied to the original field.
All chained methods you add after calling `->translatable()` will be applied to the `Filament\Forms\Components\Tabs` component.

## Laravel support

| Laravel Version | Package version |
| --------------- | --------------- |
| ^11.0           | ^1.0.2, ^2.0.0  |
| ^10.0           | ^1.0.0, ^2.0.0  |

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Special Thanks to

- [Simon Broekaert](https://github.com/SimonBroekaert)

## License

MIT License (MIT). Read the [License File](LICENSE.md) for more information.
