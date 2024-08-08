<?php

namespace App\Actions;

use App\Models\StandUpEntry;
use DOMDocument;
use DOMElement;

class ExtractStandUpEntryLinks
{
    public function execute(StandUpEntry $entry)
    {
        $content = $entry->in_progress . $entry->priorities . $entry->blockers;

        $dom = new DOMDocument();
        $dom->loadHTML($content);

        $links = [];

        /** @var DOMElement $anchor */
        foreach ($dom->getElementsByTagName('a') as $anchor) {
            $links[] = [
                'url' => $anchor->getAttribute('href'),
                'host' => parse_url($anchor->getAttribute('href'), PHP_URL_HOST),
                'text' => $anchor->textContent,
                'attributes' => $this->getAttributes($anchor),
            ];
        }

        $entry->standUpEntryLinks()->delete();

        foreach ($links as $link) {
            $entry->standUpEntryLinks()->create($link);
        }
    }

    private function getAttributes(DOMElement $anchor)
    {
        $attributes = [];

        foreach ($anchor->attributes as $attribute) {
            $attributes[$attribute->name] = $attribute->value;
        }

        return $attributes;
    }
}
