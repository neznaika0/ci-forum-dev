<?php

namespace App\Concerns;

use App\Libraries\TextFormatter;
use DOMDocument;

trait RendersContent
{
    /**
     * Generates the HTML for this record
     */
    public function render(): string
    {
        $cacheKey = $this->cacheKey('-body');

        if (! $content = cache($cacheKey)) {
            $content = match ($this->markup) {
                'bbcode' => TextFormatter::instance()->renderBBCode($this->body),
                default  => TextFormatter::instance()->renderMarkdown($this->body),
            };

            $content = $this->nofollowLinks($content);

            cache()->save($cacheKey, $content, MONTH);
        }

        return $content;
    }

    /**
     * Strips all anchor tags from the given HTML.
     *
     * Used mostly to strip out links from the rendered content
     * when not allowed by the user's trust level.
     */
    public function stripAnchors(string $html): string
    {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->loadHTML(mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, ~0], 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        foreach ($xml->getElementsByTagName('a') as $anchor) {
            $anchor->parentNode->replaceChild($xml->createTextNode($anchor->nodeValue), $anchor);
        }

        return html_entity_decode(
                mb_decode_numericentity($xml->saveHTML(), [0x80, 0x10FFFF, 0, ~0], 'UTF-8')
            );
    }

    /**
     * Adds `rel="nofollow"` to all anchor tags in the given HTML.
     * Also ensures all links open in a new tab.
     *
     * Used for most user-generated content to prevent spam.
     */
    public function nofollowLinks(string $html): string
    {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->loadHTML(mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, ~0], 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        /**
         * @var \DOMElement $anchor
         */
        foreach ($xml->getElementsByTagName('a') as $anchor) {
            $anchor->setAttribute('rel', 'nofollow');
            $anchor->setAttribute('target', '_blank');
        }

        return html_entity_decode(
                mb_decode_numericentity($xml->saveHTML(), [0x80, 0x10FFFF, 0, ~0], 'UTF-8')
            );
    }
}
