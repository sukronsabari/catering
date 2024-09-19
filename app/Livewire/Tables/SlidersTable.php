<?php

namespace App\Livewire\Tables;

use App\Models\Slider;
use Carbon\Carbon;
use App\Exports\SlidersExport;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

class SlidersTable extends DataTableComponent
{
    protected $model = Slider::class;
    public array $bulkActions = [
        'exportToExcel' => 'Export',
        'openBulkDeleteModal' => 'Delete',
        'openBulkUpdateModal' => 'Set Active',
    ];

    public function configure(): void
    {
        $this
            ->setPrimaryKey('id')
            ->setDefaultSort('id', 'asc')
            ->setComponentWrapperAttributes([
                'default' => true,
                'class' => 'px-4 py-8',
            ])
            ->setTheadAttributes([
                'default' => true,
                'class' => 'bg-gray-100',
            ]);

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'min-h-[500px]',
        ]);

        $this->setSearchDebounce(400);
        $this->setSearchPlaceholder('Search for slider...');

        $this->setConfigurableAreas([
            'before-toolbar' => [
                'components.datatables.link',
                [
                    'withIcon' => true,
                    'icon' => 'ti ti-plus',
                    'text' => 'Add Slider',
                    'id' => 'add-button-before-toolbar',
                    'href' => route('admin.sliders.create'),
                ]
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->excludeFromColumnSelect()
                ->sortable(),
            Column::make("Title", "title")
                ->excludeFromColumnSelect()
                ->searchable(),
            Column::make("Subtitle", "subtitle")
                ->excludeFromColumnSelect()
                ->searchable(),
            Column::make("Starting Price (IDR)", "starting_price")
                ->excludeFromColumnSelect()
                ->format(fn($value, $row, Column $column) => (int) $value)
                ->sortable(),
            Column::make("Position", "position")
                ->excludeFromColumnSelect()
                ->sortable(),
            BooleanColumn::make("Active", "is_active")
                ->sortable(),
            Column::make("Url", "url")
                ->collapseAlways(),
            Column::make("Image", "image")
                ->collapseAlways(),
            Column::make("Created at", "created_at")
                ->format(fn($value, $row, Column $column) => Carbon::parse($value)->format('d M Y'))
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->format(fn($value, $row, Column $column) => Carbon::parse($value)->format('d M Y'))
                ->sortable(),
            Column::make('Action')
                ->label(
                    fn($row, Column $column) => view('components.datatables.action-column')->with(
                        [
                            'editLink' => route(
                                'admin.sliders.edit',
                                [
                                    'slider' => $row,
                                    'callbackUrl' => route('admin.sliders.index', [], false)
                                ]
                            ),
                            'deleteLink' => route(
                                'admin.sliders.destroy',
                                [
                                    'slider' => $row,
                                    'callbackUrl' => route('admin.sliders.index', [], false)
                                ]
                            ),
                        ]
                    )
                ),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Active')
                ->options([
                    '' => 'All',
                    '1' => 'Yes',
                    '0' => 'No',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value === '1') {

                        /** @disregard Builder $builder */
                        $builder->where('is_active', true);
                    } elseif ($value === '0') {
                        /** @disregard Builder $builder */
                        $builder->where('is_active', false);
                    }
                }),
            TextFilter::make('Min Price')
                ->config([
                    'placeholder' => 'Enter Price'
                ])
                ->filter(function (Builder $builder, string $value) {
                    /** @disregard Builder $builder */
                    // dd($value);
                    $builder->where('starting_price', '>=', (int) $value);
                }),
            TextFilter::make('Max Price')
                ->config([
                    'placeholder' => 'Enter Price'
                ])
                ->filter(function (Builder $builder, string $value) {
                    /** @disregard Builder $builder */
                    $builder->where('starting_price', '<=', (int) $value);
                }),
            DateFilter::make('Create From')
                ->filter(function (Builder $builder, string $value) {
                    $dateTimeValue = Carbon::parse($value)->startOfDay()->toDateTimeString();

                    /** @disregard Builder $builder */
                    $builder->where('created_at', '>=', $dateTimeValue);
                }),
            DateFilter::make('Create To')
                ->filter(function (Builder $builder, string $value) {
                    $dateTimeValue = Carbon::parse($value)->endOfDay()->toDateTimeString();

                    // dd($dateTimeValue);
                    /** @disregard Builder $builder */
                    $builder->where('created_at', '<=', $dateTimeValue);
                }),
        ];
    }

    public function exportToExcel()
    {
        $selectedIds = $this->getSelected();
        return Excel::download(new SlidersExport($selectedIds), 'sliders.xlsx');
    }

    public function openBulkDeleteModal()
    {
        if (!$this->getSelected()) {
            return;
        }

        $this->dispatch('bulk-delete-sliders-modal', ids: $this->getSelected());
    }

    public function openBulkUpdateModal()
    {
        if (!$this->getSelected()) {
            return;
        }

        $this->dispatch('bulk-update-sliders-modal', ids: $this->getSelected());
    }

    #[On('bulk-delete-sliders')]
    public function deleteSelected($ids, $queryParams)
    {
        if (empty($ids)) {
            return;
        }

        DB::transaction(function () use ($ids) {
            Slider::whereIn('id', $ids)->delete();
        });

        session()->flash('toast-notification', [
            'message' => 'Selected sliders have been deleted successfully.',
            'type' => 'success',
        ]);
        $this->clearSelected();

        $callbackUrl = route('admin.sliders.index') . ($queryParams ? "?{$queryParams}" : '');
        return $this->redirect($callbackUrl);
    }

    #[On('bulk-update-sliders')]
    public function setSelectedActive($ids, $queryParams)
    {
        if (empty($ids)) {
            return;
        }

        DB::transaction(function () use ($ids) {
            Slider::whereIn('id', $ids)->update(['is_active' => true]);
        });

        session()->flash('toast-notification', [
            'message' => 'Selected sliders have been activate successfully.',
            'type' => 'success',
        ]);

        $this->clearSelected();

        $callbackUrl = route('admin.sliders.index') . ($queryParams ? "?{$queryParams}" : '');
        return $this->redirect($callbackUrl);
    }
}
