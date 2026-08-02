<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Models\Media;
use App\Services\v1\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(
        private MediaService $mediaService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->mediaService->allForUser($request->user()->id),
        ]);
    }

    public function store(StoreMediaRequest $request): JsonResponse
    {
        $media = $this->mediaService->store($request->user()->id, $request->file('file'));

        return response()->json([
            'status' => true,
            'data' => $media,
            'message' => 'File uploaded',
        ], 201);
    }

    public function destroy(Request $request, Media $media): JsonResponse
    {
        if ($media->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $this->mediaService->delete($media);

        return response()->json([
            'status' => true,
            'message' => 'File deleted',
        ]);
    }
}
