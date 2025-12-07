<?php

namespace YPdf;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Filesystem\Filesystem;
use YPdf\Contracts\PdfEngine;
use YPdf\Drivers\ChromePdfEngine;
use InvalidArgumentException;

class YPdfManager
{
    protected Repository $config;

    protected ViewFactory $viewFactory;

    protected Filesystem $files;

    protected ?PdfEngine $driver = null;

    public function __construct(Repository $config, ViewFactory $viewFactory, Filesystem $files)
    {
        $this->config = $config;
        $this->viewFactory = $viewFactory;
        $this->files = $files;
    }

    public function driver(): PdfEngine
    {
        if ($this->driver instanceof PdfEngine) {
            return $this->driver;
        }

        $driverName = $this->config->get('ypdf.driver', 'chrome');

        return $this->driver = $this->resolveDriver($driverName);
    }

    protected function resolveDriver(string $driverName): PdfEngine
    {
        return match ($driverName) {
            'chrome' => new ChromePdfEngine($this->config->get('ypdf')),
            default => throw new InvalidArgumentException("Headless PDF driver [{$driverName}] is not supported."),
        };
    }

    public function renderFromHtml(string $html, array $options = []): string
    {
        return $this->driver()->renderHtml($html, $options);
    }

    public function renderFromView(string $view, array $data = [], array $options = []): string
    {
        $html = $this->viewFactory->make($view, $data)->render();

        return $this->renderFromHtml($html, $options);
    }

    public function renderFromUrl(string $url, array $options = []): string
    {
        return $this->driver()->renderUrl($url, $options);
    }

    public function renderFromHtmlToFile(string $html, string $path, array $options = []): string
    {
        $pdf = $this->renderFromHtml($html, $options);

        $this->writeBinary($path, $pdf);

        return $path;
    }

    public function renderFromViewToFile(string $view, array $data, string $path, array $options = []): string
    {
        $pdf = $this->renderFromView($view, $data, $options);

        $this->writeBinary($path, $pdf);

        return $path;
    }

    public function renderFromUrlToFile(string $url, string $path, array $options = []): string
    {
        $pdf = $this->renderFromUrl($url, $options);

        $this->writeBinary($path, $pdf);

        return $path;
    }

    protected function writeBinary(string $path, string $contents): void
    {
        $directory = dirname($path);
        if (! $this->files->exists($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $this->files->put($path, $contents, true);
    }
}
