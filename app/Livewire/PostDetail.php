<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostDetail extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        $viewed = session()->get('viewed_posts', []);
        if (!in_array($this->post->id, $viewed)) {
            $this->post->increment('views');
            session()->push('viewed_posts', $this->post->id);
        }
    }

    public function render()
    {
        $recentPosts = Post::where('is_published', true)
            ->where('id', '!=', $this->post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('livewire.post-detail', [
            'recentPosts' => $recentPosts
        ])->layoutData([
            'title' => $this->post->seo_title ?? $this->post->title,
            'meta_description' => $this->post->seo_description ?? $this->post->excerpt ?? Str::limit(strip_tags($this->post->content), 160),
            'meta_keywords' => $this->post->seo_keywords,
            'og_image' => $this->post->thumbnail ? Storage::url($this->post->thumbnail) : null,
        ]);
    }
}
