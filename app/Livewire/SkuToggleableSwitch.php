<?php

namespace App\Livewire;

use App\Models\Sku;
use Livewire\Component;

class SkuToggleableSwitch extends Component
{
    public $sku;
    public $isActive;

    public function mount(Sku $sku)
    {
        $this->sku = $sku;
        $this->isActive = $sku->is_active;
    }

    public function toggle()
    {
        // Toggle status dan simpan perubahan
        $this->sku->is_active = !$this->isActive;
        $this->sku->save();

        // Update status di frontend
        $this->isActive = !$this->isActive;
    }

    public function render()
    {
        return view('livewire.sku-toggleable-switch');
    }
}
