<?php

namespace App\Exports;

use App\Models\Slider;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SlidersExport implements FromCollection, WithHeadings
{
    protected $sliderIds;

    public function __construct(array $sliderIds = null)
    {
        $this->sliderIds = $sliderIds;
    }

    public function collection()
    {
        $query = Slider::query();

        if ($this->sliderIds) {
            $query->whereIn('id', $this->sliderIds);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Subtitle',
            'Starting Price',
            'Active',
            'Position',
            'URL',
            'Banner',
            'Created At',
            'Updated At'
        ];
    }
}
