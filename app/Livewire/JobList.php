<?php
namespace App\Livewire;
use App\Models\Vacancy;
use Livewire\Component;
use Livewire\WithPagination;

class JobList extends Component {
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $search = '';
    public $type = '';

    public function updatingSearch() {
        $this->resetPage();
    }

    public function updatingType() {
        $this->resetPage();
    }

    public function render() {
        $vacancies = Vacancy::query()
            ->where(function($q) {
                $q->whereNull('close_date')
                  ->orWhere('close_date', '>=', now()->startOfDay());
            })
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->latest()
            ->paginate(6);

        return view('livewire.job-list', [
            'vacancies' => $vacancies
        ]);
    }
}
