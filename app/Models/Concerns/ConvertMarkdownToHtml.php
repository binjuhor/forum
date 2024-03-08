<?php

namespace App\Models\Concerns;

trait ConvertMarkdownToHtml
{
    protected static function booted()
    {
        static::saving(function (self $model) {
            $markDownData = collect(self::getMarkDownToHtmlMap())
                ->flip()
                ->map(fn ($bodyColumn) => str($model->$bodyColumn)->markdown([
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                    'max_nesting_level' => 5,
                ]));

            return $model->fill($markDownData->all());
        });
    }

    protected static function getMarkDownToHtmlMap(): array
    {
        return [
            'body' => 'html',
        ];
    }
}
