<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImageDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;
    protected $url;
    protected $filepath;
    protected $filename;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $filepath, $filename)
    {
        $this->url = $url;
        $this->filepath = $filepath;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // try {
            //code...
            $url = $this->url;
            $filepath = $this->filepath;
            $filename = $this->filename;
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);
            $image = $response->getBody();
            if (!file_exists($filepath)) {
                mkdir($filepath, 0777, true);
            }
            file_put_contents($filename, $image);
        // } catch (\Throwable $th) {
        //     if ($this->attempts() > $this->tries) {
        //         throw $th;
        //     }
        //     //throw $th;
        //     $this->release(180);
        //     return;
        // }
    }
}
