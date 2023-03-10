<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function manga($slug)
    {
        $cache = Cache::get('manga_' . $slug);
        if ($cache) {
            return Inertia::render('Public/Manga', [
                'slug' => $slug,
                'manga' => $cache,
                'url' => env('APP_URL')
            ]);
        }

        $url = env('VITE_MANGADEX_API_URL') . '/manga/' . $slug;
        $url = $url . '?includes[]=cover_art&includes[]=author&includes[]=artist&includes[]=scanlation_group&includes[]=tag';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $json = json_decode($response->getBody());

        Manga::updateOrCreate(
            ['mangadex_id' => $slug],
            ['data' => $json->data]
        );

        Cache::put('manga_' . $slug, $json->data, 60 * 24 * 7);

        return Inertia::render('Public/Manga', [
            'slug' => $slug,
            'manga' => $json->data,
            'url' => env('APP_URL'),
        ]);
        
    }

    public function chapter($slug, $chapter)
    {
        $cache = Cache::get('chapter_' . $slug . '_' . $chapter);
        if ($cache) {
            return Inertia::render('Public/Chapter', [
                'slug' => $slug,
                'chapter' => $chapter,
                'content' => $cache['content'],
                'manga' => $cache['manga'],
                'url' => env('APP_URL')
            ]);
        }

        $url = env('VITE_MANGADEX_API_URL') . '/chapter/' . $chapter;
        $url = $url . '?includes[]=scanlation_group&includes[]=scanlator&includes[]=manga&includes[]=cover_art';

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $json = json_decode($response->getBody());

        Chapter::updateOrCreate(
            ['mangadex_chapter_id' => $chapter],
            ['data' => $json->data]
        );

        $manga = Manga::where('mangadex_id', $slug)->first();
        if (!$manga) {
            $url = env('VITE_MANGADEX_API_URL') . '/manga/' . $slug;
            $url = $url . '?includes[]=cover_art&includes[]=author&includes[]=artist&includes[]=scanlation_group&includes[]=tag';
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);

            $json = json_decode($response->getBody());

            Manga::updateOrCreate(
                ['mangadex_id' => $slug],
                ['data' => $json->data]
            );

            $manga = Manga::where('mangadex_id', $slug)->first();
        }

        Cache::put('chapter_' . $slug . '_' . $chapter, [
            'content' => $json->data,
            'manga' => $manga->data
        ], 60 * 24 * 7);

        return Inertia::render('Public/Chapter', [
            'slug' => $slug,
            'chapter' => $chapter,
            'content' => $json->data,
            'manga' => $manga->data,
        ]);
    }
}
