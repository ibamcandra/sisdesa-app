<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Kabar Desa')]
class PostList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::query()
            ->where('is_published', true)
            ->when($this->search, function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function($q) {
                $q->where('category', $this->category);
            })
            ->latest('published_at')
            ->paginate(9);

        $categories = Post::where('is_published', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('livewire.post-list', [
            'posts' => $posts,
            'categories' => $categories
        ])->layoutData([
            'meta_description' => 'Kumpulan kabar terbaru, kegiatan, dan informasi resmi dari Desa Campaka. Dapatkan berita terupdate seputar pembangunan dan kegiatan desa.'
        ]);
    }
}
