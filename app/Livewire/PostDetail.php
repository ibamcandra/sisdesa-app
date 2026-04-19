<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Title;

class PostDetail extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    #[Title('Detail Kabar')]
    public function render()
    {
        $recentPosts = Post::where('is_published', true)
            ->where('id', '!=', $this->post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('livewire.post-detail', [
            'recentPosts' => $recentPosts
        ]);
    }
}
