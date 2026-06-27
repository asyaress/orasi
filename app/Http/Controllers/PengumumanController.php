<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $tag = trim((string) $request->query('tag'));

        $items = Pengumuman::query()
            ->published()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('judul', 'like', "%{$search}%")
                        ->orWhere('ringkasan', 'like', "%{$search}%")
                        ->orWhere('konten', 'like', "%{$search}%");
                });
            })
            ->when($tag !== '', fn (Builder $query) => $query->whereJsonContains('tags', $tag))
            ->publicOrder()
            ->paginate(9)
            ->withQueryString();

        return view('pages.pengumuman.index', compact(
            'items',
            'search',
            'tag'
        ) + $this->heroVideoData());
    }

    public function show(Pengumuman $pengumuman): View
    {
        abort_unless(
            $pengumuman->status === 'published'
                && ($pengumuman->published_at === null || $pengumuman->published_at->isPast()),
            404
        );

        $related = Pengumuman::query()
            ->published()
            ->whereKeyNot($pengumuman->getKey())
            ->when(! empty($pengumuman->tags), function (Builder $query) use ($pengumuman): void {
                $query->where(function (Builder $query) use ($pengumuman): void {
                    foreach ($pengumuman->tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                });
            })
            ->publicOrder()
            ->limit(3)
            ->get();

        if ($related->count() < 3) {
            $additional = Pengumuman::query()
                ->published()
                ->whereKeyNot($pengumuman->getKey())
                ->whereNotIn('id', $related->modelKeys())
                ->publicOrder()
                ->limit(3 - $related->count())
                ->get();

            $related = $related->concat($additional);
        }

        return view('pages.pengumuman.show', compact('pengumuman', 'related') + $this->heroVideoData());
    }

    private function heroVideoData(): array
    {
        $videoId = 'S_9XNYv6RZo';
        $origin = urlencode(url('/'));

        return [
            'heroYoutubeEmbedUrl' => "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&controls=0&loop=1&playlist={$videoId}&playsinline=1&rel=0&modestbranding=1&showinfo=0&iv_load_policy=3&disablekb=1&fs=0&enablejsapi=1&origin={$origin}",
            'heroBackground' => asset('images/background/1.webp'),
        ];
    }
}
