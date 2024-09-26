<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sku;
use Livewire\Attributes\On;

class SkuToggleableDefault extends Component
{
    public $sku;
    public $is_default;

    public function mount(Sku $sku)
    {
        $this->sku = $sku;
        $this->is_default = $sku->is_default;
    }

    public function setAsDefault()
    {
        $this->sku->product->skus()->update(['is_default' => false]);
        $this->sku->update(['is_default' => true]);

        $this->is_default = true;
        $this->dispatch('sku-updated.' . $this->sku->product_id, skuId: $this->sku->id);
    }

    #[On('sku-updated.{sku.product_id}')]
    public function refreshStatus($skuId)
    {
        if ($this->sku->id !== $skuId) {
            $this->is_default = false;
        }
    }

    public function render()
    {
        return view('livewire.sku-toggleable-default');
    }
}
