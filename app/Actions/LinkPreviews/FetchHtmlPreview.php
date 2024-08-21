<?php

namespace App\Actions\LinkPreviews;

use DOMDocument;
use Illuminate\Support\Facades\Http;
use const PHP_URL_HOST;
use const PHP_URL_SCHEME;

class FetchHtmlPreview implements FetchServicePreview
{
    public function execute(string $url) : LinkPreviewDto {
        $html = Http::withUserAgent('Mozilla/5.0 (platform; rv:geckoversion) Gecko/geckotrail Firefox/firefoxversion')
            ->get($url)->body();
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $nodes = $doc->getElementsByTagName('title');

        //get and display what you need:
        $title = $nodes->item(0)?->nodeValue;

        $metas = $doc->getElementsByTagName('meta');

        $description = '';
        $image = '';
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('name') == 'description') {
                $description = $meta->getAttribute('content');
            }
        }

        //find favicon
        $links = $doc->getElementsByTagName('link');
        foreach ($links as $link) {
            if ($link->getAttribute('rel') == 'icon') {
                $faviconRelativePath = $link->getAttribute('href');
                //check if relative path
                //if so, then prepend the base url

                if (strpos($faviconRelativePath, 'http') === false) {
                    $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
                    $image = $baseUrl . $faviconRelativePath;
                } else {
                    $image = $faviconRelativePath;
                }
            }
        }

        return new LinkPreviewDto(
            title: $title,
            description: $description,
            image: $image,
            url: $url,
        );
    }
}
