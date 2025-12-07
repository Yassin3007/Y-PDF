<?php

namespace Imar\HeadlessPdf\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string renderFromHtml(string $html, array $options = [])
 * @method static string renderFromView(string $view, array $data = [], array $options = [])
 * @method static string renderFromUrl(string $url, array $options = [])
 * @method static string renderFromHtmlToFile(string $html, string $path, array $options = [])
 * @method static string renderFromViewToFile(string $view, array $data, string $path, array $options = [])
 * @method static string renderFromUrlToFile(string $url, string $path, array $options = [])
 */
class HeadlessPdf extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'headlesspdf';
    }
}
