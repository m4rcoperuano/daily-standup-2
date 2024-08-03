<?php

namespace App\Http\Controllers;

use DOMDocument;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchLinkPreviewController extends Controller
{
    //TODO: Although this works, its ugly, and needs to be refactored to smaller components
    //Also, some websites will not return a valid response, such as those requiring a login.
    //Therefore, this API needs to work with Saloon, an API plugin that lets us build integrations with other tools
    //I imagine something like - if I paste in a URL and its detected that its from Github, then we can use the Github API to fetch the data
    //The user should be prompted in the frontend to provide us with a personal access token (so we don't need to go through the OAuth flow).
    //This token should be stored in the database, and used to make requests to the Github API.
    //Likewise with JIRA

    public function __invoke(Request $request)
    {
        $url = $request->url;
        //the url may sometimes be too big to store as a key in the hash table
        $cacheKey = md5($url);

        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
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
                else {
                    $image = $faviconRelativePath;
                }
            }
        }

        Cache::put($cacheKey, [
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
