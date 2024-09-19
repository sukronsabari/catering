<?php

namespace App\Livewire\Sliders;

use App\Models\Slider;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddSliderForm extends Component
{
    use WithFileUploads;

    public $title = '';
    public $subtitle = '';
    public $starting_price = 0;
    public $position;
    public $is_active = '0';
    public $url = '';
    public $banner;

    public $activeOptions = [
        ['label' => 'Active', 'value' => '1', 'selected' => false],
        ['label' => 'Non Active', 'value' => '0', 'selected' => true],
    ];

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'subtitle' => ['required', 'string', 'min:3', 'max:255'],
            'starting_price' => ['required', 'numeric', 'min:0'],
            'position' => ['required', 'numeric', 'min:1'],
            'is_active' => ['required', 'in:true,false'],
            'url' => ['required', 'string', 'min:3', 'max:255'],
            'banner' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5000'],
        ];
    }

    public function save()
    {

        $validatedPayload = $this->validate();
        $startingPrice = (int) $this->starting_price;
        $position = (int) $this->position;
        $isActive = $this->is_active === 'true' ? true : false;

        $bannerPath = $this->banner->store('/images/banners', ['disk' => 'public']);
        Slider::create([
            ...$validatedPayload,
            'starting_price' => $startingPrice,
            'position' => $position,
            'banner' => $bannerPath,
            'is_active' => $isActive,
        ]);

        session()->flash('toast-notification', [
            'type' => 'success',
            'message' => 'New slider has been added!',
        ]);

        $this->dispatch('slider-created');
        $this->reset(['title', 'subtitle', 'starting_price', 'url', 'position', 'is_active', 'banner']);
    }

    public function render()
    {
        return view('livewire.sliders.add-slider-form');
    }
}
