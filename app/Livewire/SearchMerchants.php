<?php

namespace App\Livewire;

use App\Models\Merchant;
use Livewire\Component;
use Livewire\Attributes\On;

class SearchMerchants extends Component
{
    public $selectedMerchant;
    public $isEdit = false;

    public function mount($selectedMerchantId = null, $isEdit = false)
    {
        $this->isEdit = $isEdit;
        $oldMerchantId = (int) old('merchant_id');

        if ($oldMerchantId) {
            return $this->selectedMerchant = Merchant::select('id', 'name')->find($oldMerchantId);
        } else if ($selectedMerchantId) {
            return $this->selectedMerchant = Merchant::select('id', 'name')->find($selectedMerchantId);
        }
    }

    public function getMerchants($query)
    {
        return collect(Merchant::select('id', 'name')
            ->where('name', 'like', '%' . $query . '%')
            ->take(10)
            ->get());
    }

    public function getRecentMerchants()
    {
        // Mengambil 5 merchants terbaru untuk ditampilkan di dropdown
        return collect(Merchant::select('id', 'name')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get());
    }

    public function render()
    {
        return view('livewire.search-merchants', [
            'recentMerchants' => $this->getRecentMerchants() // Mengirim merchants terbaru ke view
        ]);
    }
}
