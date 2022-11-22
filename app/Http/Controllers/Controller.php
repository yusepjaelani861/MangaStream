<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($data, $message = 'Success getting data', $pagination = [])
    {
        $response = [
            'result' => 'ok',
            'response' => 'colections',
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination,
        ];

        return response()->json($response, 200);
    }
}
