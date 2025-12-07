# Y-PDF

A Laravel-ready package that uses a headless Chromium instance to render **full HTML, CSS, JavaScript, Bootstrap, Tailwind, and jQuery experiences** into pixel-perfect PDFs. Stop fighting traditional PHP PDF libraries that ignore styles — render the same markup your frontend already produces.

## Highlights

- Uses [chrome-php/chrome](https://github.com/chrome-php/chrome), so anything Chromium can paint (Vue, React, Livewire, Blade + Tailwind, etc.) will appear in the PDF.
- Simple API: `YPdf::renderFromView()` and `YPdf::renderFromHtml()` return binary data or save directly to disk.
- Configurable viewport, margins, background printing, sandbox flags, and custom wait strategies before capturing the PDF.
- Ships with a publishable config file so each project can point to its own Chromium binary, temp directory, and timeouts.
- Designed to live inside its own repository — drop this folder into a separate git repo, push to GitHub, and submit to Packagist. Afterwards, require it in any Laravel app with Composer.

## Package Structure

```
packages/
└── imar/
    └── ypdf/
        ├── composer.json
        ├── config/ypdf.php
        ├── src/
        │   ├── Contracts/
        │   ├── Drivers/
        │   ├── Facades/
        │   ├── YPdfManager.php
        │   └── YPdfServiceProvider.php
        └── README.md
```

## Installation (after publishing to Packagist)

```bash
composer require y/ypdf
php artisan vendor:publish --tag=ypdf-config
```

Ensure Chromium is installed on the server, or point `HEADLESS_PDF_CHROME_BINARY` to a bundled binary. Example `.env` entries:

```
YPDF_CHROME_BINARY=/usr/bin/google-chrome-stable
YPDF_TEMP=/var/tmp/ypdf
YPDF_NO_SANDBOX=true
```

## Usage

```php
use YPdf;

// Render a Blade view straight to disk
YPdf::renderFromViewToFile('pdfs.invoice', ['order' => $order], storage_path('app/pdfs/order.pdf'));

// Grab the binary and stream it to the browser
return response(
    YPdf::renderFromHtml($html),
    200,
    ['Content-Type' => 'application/pdf']
);
```

Need to render an authenticated page? Generate a signed URL and call `YPdf::renderFromUrl($url)`.

## Publishing Instructions

1. Move `packages/imar/ypdf` into its own git repository (e.g., GitHub).
2. Run `composer install` inside the package directory to pull dependencies.
3. Tag a release and submit the repo to [packagist.org](https://packagist.org/).
4. Back in your Laravel app, run `composer require y/ypdf`. Composer will pull the published package instead of the local path copy.

## Roadmap

- Additional drivers (Playwright, remote rendering service)
- Queued rendering + retry helpers
- HTML pre-processors for asset inlining and critical CSS extraction

Contributions welcome once the package is in its own repository!
