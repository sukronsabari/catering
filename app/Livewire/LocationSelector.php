<?php

namespace App\Livewire;

use Livewire\Component;
use Laravolt\Indonesia\Facade as Indonesia;

class LocationSelector extends Component
{
    public $provinces;
    public $cities = [];
    public $districts = [];
    public $villages = [];

    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedVillage = null;

    public function mount()
    {
        $this->provinces = Indonesia::allProvinces()->pluck('name', 'id');

        // Set initial values from old input if available
        $this->selectedProvince = old('province', '');
        $this->selectedCity = old('city', '');
        $this->selectedDistrict = old('district', '');
        $this->selectedVillage = old('village', '');

        // Load dependent data based on old values
        if ($this->selectedProvince) {
            $this->cities = Indonesia::findProvince($this->selectedProvince, ['cities'])->cities->pluck('name', 'id');
        }

        if ($this->selectedCity) {
            $this->districts = Indonesia::findCity($this->selectedCity, ['districts'])->districts->pluck('name', 'id');
        }

        if ($this->selectedDistrict) {
            $this->villages = Indonesia::findDistrict($this->selectedDistrict, ['villages'])->villages->pluck('name', 'id');
        }
    }

    // public function mount()
    // {
    //     $this->provinces = Indonesia::allProvinces()->pluck('name', 'id')->toArray();
    //     $this->cities = [];
    //     $this->districts = [];
    //     $this->villages = [];
    // }

    public function updatedSelectedProvince($provinceId)
    {
        if ($provinceId) {
            $this->cities = Indonesia::findProvince($provinceId, ['cities'])->cities->pluck('name', 'id')->toArray();
        } else {
            $this->cities = [];
        }

        $this->reset(['selectedCity', 'selectedDistrict', 'selectedVillage', 'districts', 'villages']);
    }

    public function updatedSelectedCity($cityId)
    {
        if ($cityId) {
            $this->districts = Indonesia::findCity($cityId, ['districts'])->districts->pluck('name', 'id')->toArray();
        } else {
            $this->districts = [];
        }

        $this->reset(['selectedDistrict', 'selectedVillage', 'villages']);
    }

    public function updatedSelectedDistrict($districtId)
    {
        if ($districtId) {
            $this->villages = Indonesia::findDistrict($districtId, ['villages'])->villages->pluck('name', 'id')->toArray();
        } else {
            $this->villages = [];
        }

        $this->reset(['selectedVillage']);
    }

    public function render()
    {
        return view('livewire.location-selector');
    }
}
