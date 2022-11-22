<?php

namespace App\Http\Controllers;

use App\Jobs\ImageDownload;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function get($mangadex_id, $filename)
    {
        $url = 'https://mangadex.org' . '/covers/' . $mangadex_id . '/' . $filename;
        $filepath = public_path('image');
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }

        $name = $filepath . '/' . $mangadex_id . '/' . $filename;

        if (!file_exists($name)) {
            dispatch(new ImageDownload($url, $filepath . '/' . $mangadex_id, $name));
            // $client = new \GuzzleHttp\Client();
            // $response = $client->request('GET', $url);
            // $image = $response->getBody();
            // if (!file_exists($filename)) {
            //     mkdir($filepath . '/' . $mangadex_id, 0777, true);
            // }
            // file_put_contents($filename, $image);
            return redirect()->to('https://i0.wp.com/mangadex.org/covers/' . $mangadex_id . '/' . $filename);
        }

        return response()->file($filename);
    }

    public function getChapterImages($base_url, $hash, $filename)
    {
        $url = 'https://' . $base_url . '/data-saver/' . $hash . '/' . $filename;
        $filepath = public_path('data-saver/' . $base_url . '/data-saver/' . $hash);
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }

        $name = $filepath . '/' . $filename;

        if (!file_exists($name)) {
            dispatch(new ImageDownload($url, $filepath, $name));

            return redirect()->to('https://i0.wp.com/' . $base_url . '/data-saver/' . $hash . '/' . $filename);
        }

        return response()->file($filename);

    }
}
