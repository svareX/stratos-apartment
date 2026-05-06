<?php

namespace App\Services;

use App\Models\InstagramPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class InstagramSyncService
{
    public function sync(?string $userId = null, int $limit = 6): void
    {
        $userId = $userId ?? config('services.instagram.user_id');

        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'instagram-looter2.p.rapidapi.com',
            'X-RapidAPI-Key' => config('services.instagram.rapidapi_key'),
        ])->get('https://instagram-looter2.p.rapidapi.com/user-feeds2', [
            'id' => $userId,
            'count' => $limit,
        ]);

        if ($response->failed()) {
            logger()->error('Failed to fetch Instagram posts from API', [
                'user_id' => $userId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return;
        }

        if ($response->successful()) {
            $data = $response->json();

            $items = data_get($data, 'data.user.edge_owner_to_timeline_media.edges', []);

            foreach ($items as $item) {
                $node = $item['node'] ?? [];

                if (empty($node)) {
                    continue;
                }

                $instagramId = $node['id'];
                $imageUrl = $node['thumbnail_src'] ?? $node['display_url'] ?? '';
                $localPath = '';

                if (! empty($imageUrl)) {
                    $imageContents = Http::get($imageUrl)->body();
                    $filename = 'instagram/'.$instagramId.'.jpg';
                    Storage::disk('public')->put($filename, $imageContents);
                    $localPath = $filename;
                }

                InstagramPost::updateOrCreate(
                    ['instagram_id' => $instagramId],
                    [
                        'image_url' => $localPath,
                        'url' => 'https://instagram.com/p/'.($node['shortcode'] ?? ''),
                        'caption' => data_get($node, 'edge_media_to_caption.edges.0.node.text'),
                        'posted_at' => isset($node['taken_at_timestamp']) ? Carbon::createFromTimestamp($node['taken_at_timestamp']) : now(),
                    ]
                );
            }
        }
    }
}
