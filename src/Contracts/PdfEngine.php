<?php

namespace Imar\HeadlessPdf\Contracts;

interface PdfEngine
{
    /**
     * Render raw HTML into a PDF binary string.
     */
    public function renderHtml(string $html, array $options = []): string;

    /**
     * Render a remote URL into a PDF binary string.
     */
    public function renderUrl(string $url, array $options = []): string;
}
