<?php

namespace IdeoLearn\Core\Traits;

use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Arr;

trait HasCustomTranslations
{
    use HasTranslations;

    /**
     * Get translations in locale-based format
     *
     * @return array
     */
    public function getTranslationsAttribute(): array
    {
        $translations = [];
        foreach ($this->translatable as $field) {
            $fieldTranslations = $this->getTranslations($field);
            foreach ($fieldTranslations as $locale => $value) {
                $translations[$locale] = $value;
            }
        }
        return $translations;
    }

    /**
     * Set translations from locale-based format including 'all' key handling
     *
     * @param array $translations
     * @param string $field
     * @return void
     */
    public function setCustomTranslations(array $translations, string $field): void
    {
        // Handle 'all' key if present
        if (isset($translations['all'])) {
            $supportedLocales = array_keys(config('laravellocalization.supportedLocales'));
            foreach ($supportedLocales as $locale) {
                $this->setTranslation($field, $locale, $translations['all']);
            }
            unset($translations['all']);
        }

        // Handle specific locale translations
        foreach ($translations as $locale => $value) {
            if ($value !== null) {
                $this->setTranslation($field, $locale, $value);
            }
        }
    }
}
