<?php

namespace App\Http\Controllers\APIs\Mangadex;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Covers;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MangaController extends Controller
{
    public function getList(Request $request)
    {
        $cache = Cache::get('mangadex_list' . $request->query('limit') . $request->query('offset'));
        if ($cache) {
            return $cache;
        }

        $url = env('VITE_MANGADEX_API_URL') . '/manga';
        if ($request->page) {
            $offset = $request->page * 16;
            $request->offset = $offset;
        }

        $query = [
            'limit' => $request->limit,
            'offset' => $request->offset,
            'title' => $request->title,
            'contentRating' => $request->contentRating,
            'publicationDemographic' => $request->publicationDemographic,
            'status' => $request->status,
            'originalLanguage' => $request->originalLanguage,
            'createdAtSince' => $request->createdAtSince,
            'updatedAtSince' => $request->updatedAtSince,
            'order[createdAt]' => $request->orderCreatedAt,
            'order[updatedAt]' => $request->orderUpdatedAt,
            'order[popularity]' => $request->orderPopularity,
            'order[trending]' => $request->orderTrending,
            'ids[]' => $request->ids,
            'authors[]' => $request->authors,
            'artists[]' => $request->artists,
            'year[]' => $request->year,
            'includedTags[]' => $request->includedTags,
            'excludedTags[]' => $request->excludedTags,
            'includedTagsMode' => $request->includedTagsMode,
            'excludedTagsMode' => $request->excludedTagsMode,
            'originalLanguage[]' => $request->originalLanguage,
            'publicationDemographic[]' => $request->publicationDemographic,
            'contentRating[]' => $request->contentRating,
            'status[]' => $request->status,
            'version' => $request->version,
            'hentai' => $request->hentai,
        ];

        // convert to url query string
        $query = http_build_query($query);
        $url = $url . '?' . $query . '&includes[]=cover_art&includes[]=chapter';

        // return $url;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        Cache::put('mangadex_list'  . $request->query('limit') . $request->query('offset'), json_encode(json_decode($response->getBody())), 60 * 3);
        // return $response->getBody();
        return response()->json(json_decode($response->getBody()));
    }

    public function getChapter(Request $request)
    {
        $cache = Cache::get('mangadex_chapter_' . $request->manga_id);
        if ($cache) {
            return $cache;
        }
        $url = env('VITE_MANGADEX_API_URL') . '/chapter?manga=' . $request->manga_id;
        $limit = $request->limit ?? 100;
        $url = $url . '&translatedLanguage[]=en&includes[]=scanlation_group&includes[]=scanlator&includes[]=manga&includes[]=cover_art&limit=' . $limit;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $json = json_decode($response->getBody());

        Cache::put('mangadex_chapter_' . $request->manga_id, json_encode($json), 60 * 12);

        return response()->json(json_decode($response->getBody()));
    }

    public function getImages($chapter)
    {
        $covers = Covers::where('mangadex_chapter_id', $chapter)->first();
        if ($covers) {
            return response()->json($covers->data);
        }
        $url = env('VITE_MANGADEX_API_URL') . '/at-home/server/' . $chapter;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $json = json_decode($response->getBody());

        $covers = new Covers();
        $covers->mangadex_chapter_id = $chapter;
        $covers->data = $json;
        $covers->save();

        return response()->json($json);
    }

    public function getDetail($mangadex_id) 
    {
        $manga = Manga::where('mangadex_id', $mangadex_id)->first();
        if ($manga) {
            return $this->sendResponse($manga->data, 'Manga found', []);
        }

        $url = env('VITE_MANGADEX_API_URL') . '/manga/' . $mangadex_id;
        $url = $url . '?includes[]=cover_art&includes[]=author&includes[]=artist&includes[]=scanlation_group&includes[]=tag';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $json = json_decode($response->getBody());

        Manga::updateOrCreate(
            ['mangadex_id' => $mangadex_id],
            ['data' => $json->data]
        );

        return $this->sendResponse($json->data, 'Manga found', []);
    }

    public function search(Request $request)
    {
        $url = env('VITE_MANGADEX_API_URL') . '/manga';
        $query = [
            'title' => $request->title,
            'contentRating' => $request->contentRating,
            'publicationDemographic' => $request->publicationDemographic,
            'status' => $request->status,
            'originalLanguage' => $request->originalLanguage,
            'createdAtSince' => $request->createdAtSince,
            'updatedAtSince' => $request->updatedAtSince,
            'order[createdAt]' => $request->orderCreatedAt,
            'order[updatedAt]' => $request->orderUpdatedAt,
            'order[popularity]' => $request->orderPopularity,
            'order[trending]' => $request->orderTrending,
            'ids[]' => $request->ids,
            'authors[]' => $request->authors,
            'artists[]' => $request->artists,
            'year[]' => $request->year,
            'includedTags[]' => $request->includedTags,
            'excludedTags[]' => $request->excludedTags,
            'includedTagsMode' => $request->includedTagsMode,
            'excludedTagsMode' => $request->excludedTagsMode,
            'originalLanguage[]' => $request->originalLanguage,
            'publicationDemographic[]' => $request->publicationDemographic,
            'contentRating[]' => $request->contentRating,
            'status[]' => $request->status,
            'version' => $request->version,
            'hentai' => $request->hentai,
        ];

        // convert to url query string
        $query = http_build_query($query);
        $url = $url . '?' . $query . '&includes[]=cover_art&includes[]=chapter';

        // return $url;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        // return $response->getBody();
        return response()->json(json_decode($response->getBody()));
    }
}
