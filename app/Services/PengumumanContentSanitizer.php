<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class PengumumanContentSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'div', 'span', 'strong', 'b', 'em', 'i', 'u', 's',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'ol', 'li',
        'blockquote', 'a', 'img', 'table', 'thead', 'tbody', 'tfoot',
        'tr', 'th', 'td', 'hr', 'pre', 'code',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'href', 'src', 'alt', 'title', 'target', 'rel', 'class', 'style',
        'width', 'height', 'colspan', 'rowspan',
    ];

    public function sanitize(string $html): string
    {
        if (trim($html) === '' || ! class_exists(DOMDocument::class)) {
            return strip_tags($html, '<p><br><div><span><strong><b><em><i><u><s><h1><h2><h3><h4><h5><h6><ul><ol><li><blockquote><a><img><table><thead><tbody><tfoot><tr><th><td><hr><pre><code>');
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="pengumuman-content">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);
        /** @var DOMElement $element */
        foreach (iterator_to_array($xpath->query('//*') ?: []) as $element) {
            $tag = strtolower($element->tagName);

            if ($element->getAttribute('id') === 'pengumuman-content') {
                $element->removeAttribute('id');

                continue;
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $this->unwrap($element);

                continue;
            }

            foreach (iterator_to_array($element->attributes) as $attribute) {
                $name = strtolower($attribute->name);

                if (! in_array($name, self::ALLOWED_ATTRIBUTES, true)) {
                    $element->removeAttribute($attribute->name);

                    continue;
                }

                if (in_array($name, ['href', 'src'], true) && ! $this->isSafeUrl($attribute->value, $tag === 'img')) {
                    $element->removeAttribute($attribute->name);
                }
            }

            if ($tag === 'a' && strtolower($element->getAttribute('target')) === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        $root = $document->documentElement;
        if (! $root) {
            return '';
        }

        return collect(iterator_to_array($root->childNodes))
            ->map(fn (DOMNode $node): string => $document->saveHTML($node) ?: '')
            ->implode('');
    }

    private function unwrap(DOMElement $element): void
    {
        $parent = $element->parentNode;
        if (! $parent) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private function isSafeUrl(string $url, bool $isImage): bool
    {
        $url = trim(html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($url === '' || str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, $isImage ? ['http', 'https'] : ['http', 'https', 'mailto', 'tel'], true);
    }
}
