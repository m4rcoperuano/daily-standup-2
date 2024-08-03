<?php

namespace App\Http\Controllers;

use DOMDocument;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchLinkPreviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $url = $request->url;
        if (Cache::has($url)) {
            //return response()->json(Cache::get($url));
        }

        $html = Http::get($url)->body();
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $nodes = $doc->getElementsByTagName('title');

        //get and display what you need:
        $title = $nodes->item(0)->nodeValue;

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
                }
            }
        }

        Cache::put($url, [
            'title' => $title,
            'description' => $description,
            'image' => $image,
        ], now()->addDay());

        return response()->json([
            'title' => $title,
            'description' => $description,
            'image' => $image,
        ]);
    }
}
