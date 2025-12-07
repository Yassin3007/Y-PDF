<?php

namespace YPdf\Drivers;

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Page;
use YPdf\Contracts\PdfEngine;
use RuntimeException;

class ChromePdfEngine implements PdfEngine
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function renderHtml(string $html, array $options = []): string
    {
        return $this->withPage(function (Page $page) use ($html, $options) {
            $page->setHtml($html, ['waitUntil' => $options['waitUntil'] ?? 'networkIdle']);

            return $this->generatePdf($page, $options);
        }, $options);
    }

    public function renderUrl(string $url, array $options = []): string
    {
        return $this->withPage(function (Page $page) use ($url, $options) {
            $page->navigate($url)->waitForNavigation($options['waitUntil'] ?? Page::NETWORK_IDLE);

            return $this->generatePdf($page, $options);
        }, $options);
    }

    protected function withPage(callable $callback, array $options = []): string
    {
        $browserFactory = new BrowserFactory($this->config['chrome']['binary_path'] ?? null);
        $browserOptions = $this->buildBrowserOptions($options);

        $browser = $browserFactory->createBrowser($browserOptions);

        try {
            $page = $browser->createPage();
            $viewport = $this->config['chrome']['viewport'] ?? [];
            if (! empty($viewport)) {
                $page->setViewport($viewport['width'] ?? 1280, $viewport['height'] ?? 720, $viewport['deviceScaleFactor'] ?? 1);
            }

            $result = $callback($page);
            $page->close();

            return $result;
        } catch (\Throwable $exception) {
            throw new RuntimeException('Unable to generate PDF via Chrome driver: ' . $exception->getMessage(), previous: $exception);
        } finally {
            $browser->close();
        }
    }

    protected function generatePdf(Page $page, array $options = []): string
    {
        $pdfOptions = array_merge($this->config['chrome']['pdf'] ?? [], $options['pdf'] ?? []);

        $result = $page->pdf(array_filter($pdfOptions, fn ($value) => $value !== null));

        return base64_decode($result->getBase64(), true) ?: '';
    }

    protected function buildBrowserOptions(array $options = []): array
    {
        $chromeConfig = $this->config['chrome'] ?? [];

        $args = [];
        if ($chromeConfig['no_sandbox'] ?? false) {
            $args[] = '--no-sandbox';
        }
        if (! ($chromeConfig['enable_images'] ?? true)) {
            $args[] = '--blink-settings=imagesEnabled=false';
        }

        return array_filter([
            'headless' => true,
            'connectionDelay' => 0.8,
            'sendSyncDefaultTimeout' => ($chromeConfig['timeout'] ?? 60) * 1000,
            'customFlags' => $args,
            'userDataDir' => $this->config['temp_directory'] ?? null,
            'windowSize' => [
                $chromeConfig['viewport']['width'] ?? 1280,
                $chromeConfig['viewport']['height'] ?? 720,
            ],
            'enableImages' => $chromeConfig['enable_images'] ?? true,
        ], fn ($value) => $value !== null);
    }
}
