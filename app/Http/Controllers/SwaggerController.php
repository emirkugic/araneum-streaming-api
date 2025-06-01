<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SwaggerController extends Controller
{
    public function index()
    {
        return view('swagger.index');
    }

    public function docs()
    {
        $openapi = \OpenApi\Generator::scan([app_path('Http/Controllers')]);

        foreach ($openapi->paths as $path) {
            if (empty($path->operations)) {
                $path->operations = [];
            }
        }

        return Response::make($openapi->toJson(), 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
