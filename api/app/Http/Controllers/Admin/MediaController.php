<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\v1\MediaService;
use Illuminate\Http\JsonResponse;

class MediaController extends Controller
{
    public function __construct(
        private MediaService $mediaService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->mediaService->allForAdmin(),
        ]);
    }
}
