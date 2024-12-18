<?php

namespace Go2Stone\FilamentTranslatable\Filament\Plugins;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Tabs;
use Filament\Panel;

class FilamentTranslatablePlugin implements Plugin
{
    protected array|Closure $supportedLocales = [];
    protected bool $useLocaleTabLabels = false;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'outerweb-filament-translatable-fields';
    }

    public function supportedLocales(array|Closure $supportedLocales): static
    {
        $this->supportedLocales = $supportedLocales;

        return $this;
    }

    public function getSupportedLocales(): array
    {
        $locales = is_callable($this->supportedLocales) ? call_user_func($this->supportedLocales) : $this->supportedLocales;

        if (empty($locales)) {
            $locales[] = config('app.locale');
        }

        return $locales;
    }

    public function localeTabLabels(bool $localeTabLabels = false): static
    {
        $this->useLocaleTabLabels = $localeTabLabels;

        return $this;
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        $supportedLocales = $this->getSupportedLocales();
        $useLocaleTabLabels = $this->useLocaleTabLabels;

        Field::macro('translatable', function (bool $translatable = true, ?array $customLocales = null, ?array $localeSpecificRules = null) use ($supportedLocales, $useLocaleTabLabels) {
            if (! $translatable) {
                return $this;
            }

            /**
             * @var Field $field
             * @var Field $this
             */
            $field = $this->getClone();

            $tabs = collect($customLocales ?? $supportedLocales)
                ->map(function ($label, $key) use ($field, $localeSpecificRules, $useLocaleTabLabels) {

                    $locale = is_string($key) ? $key : $label;

                    $localeTabLabel = $useLocaleTabLabels
                        ? locale_get_display_name($locale, app()->getLocale()) : (is_string($key) ? $label : strtoupper($locale));
                    //$localeLabel = locale_get_display_name($locale, app()->getLocale()) ?? (is_string($key) ? $label : strtoupper($locale));

                    $clone = $field
                        ->getClone()
                        ->name("{$field->getName()}.{$locale}")
                        ->label($field->getLabel())
                        ->statePath("{$field->getStatePath(false)}.{$locale}");

                    if ($localeSpecificRules && isset($localeSpecificRules[$locale])) {
                        $clone->rules($localeSpecificRules[$locale]);
                        
                        // Workaround to add required styling to specific locales without using the required() method
                        if (in_array('required', $clone->getValidationRules())) {
                            $clone->required();
                        }
                    }


                    return Tabs\Tab::make($locale)
                        ->label($localeTabLabel)
                        ->schema([$clone]);
                })
                ->toArray();

            $tabsField = Tabs::make('translations')
                ->tabs($tabs);

            return $tabsField;
        });
    }
}
