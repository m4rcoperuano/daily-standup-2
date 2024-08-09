<?php

namespace App\Http\Controllers;

use App\Actions\LinkPreviews\FetchHtmlPreview;
use App\Actions\LinkPreviews\FetchPreview;
use App\Models\StandUpEntryLink;
use DOMDocument;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchLinkPreviewController extends Controller
{
    public function __invoke(Request $request, StandUpEntryLink $link)
    {
        $this->authorize('view', $link);

        try {
            $dto = app(FetchPreview::class)->execute($request->user(), $link);
            return response()->json($dto);
        }
        catch (Throwable $e) {
            return response()->json([
                'title' => "$link->url",
                'description' => 'An error occurred while fetching the link preview',
                'image' => '',
                'url' => $link->url,
            ]);
        }
    }
}
