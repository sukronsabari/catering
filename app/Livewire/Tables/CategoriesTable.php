<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class CategoriesTable extends DataTableComponent
{
    protected $model = Category::class;

    public array $bulkActions = [
        'openBulkDeleteModal' => 'Delete',
        'openBulkUpdateModal' => 'Set Featured',
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
            ])
            ->setTableWrapperAttributes([
                'default' => true,
                'class' => 'min-h-[500px]',
            ]);

        $this->setSearchDebounce(400);
        $this->setSearchPlaceholder('Search for category...');

        $this->setConfigurableAreas([
            'before-toolbar' => [
                'components.datatables.link',
                [
                    'withIcon' => true,
                    'icon' => 'ti ti-plus',
                    'text' => 'Add Category',
                    'id' => 'add-button-before-toolbar',
                    'href' => route('admin.products.categories.create'),
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
            Column::make("Name", "name")
                ->excludeFromColumnSelect()
                ->searchable(),
            Column::make("Parent", "parent.name")
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make("Slug", "slug")
                ->excludeFromColumnSelect(),
            BooleanColumn::make("Featured", "featured")
                ->sortable(),
            Column::make("Description", "description")
                ->collapseAlways(),
            Column::make("Image", "image")
                ->collapseAlways(),
            Column::make("Icon", "icon")
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
                            'editLink' => route('admin.products.categories.edit', [
                                'category' => $row,
                                'callbackUrl' => route('admin.products.categories.index'),
                            ]),
                            'deleteLink' => route(
                                'admin.products.categories.destroy',
                                [
                                    'category' => $row,
                                    'callbackUrl' => route('admin.products.categories.index', [], false)
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
            SelectFilter::make('Featured')
                ->options([
                    '' => 'All',
                    '1' => 'Yes',
                    '0' => 'No',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value === '1') {
                        /** @disregard Builder $builder */
                        $builder->where('categories.featured', true);
                    } elseif ($value === '0') {
                        /** @disregard Builder $builder */
                        $builder->where('categories.featured', false);
                    }
                }),
            DateFilter::make('Create From')
                ->filter(function (Builder $builder, string $value) {
                    $dateTimeValue = Carbon::parse($value)->startOfDay()->toDateTimeString();

                    /** @disregard Builder $builder */
                    $builder->where('categories.created_at', '>=', $dateTimeValue);
                }),
            DateFilter::make('Create To')
                ->filter(function (Builder $builder, string $value) {
                    $dateTimeValue = Carbon::parse($value)->endOfDay()->toDateTimeString();

                    /** @disregard Builder $builder */
                    $builder->where('categories.created_at', '<=', $dateTimeValue);
                }),
        ];
    }

    public function openBulkDeleteModal()
    {
        if (!$this->getSelected()) {
            return;
        }

        $this->dispatch('bulk-delete-categories-modal', ids: $this->getSelected());
    }

    public function openBulkUpdateModal()
    {
        if (!$this->getSelected()) {
            return;
        }

        $this->dispatch('bulk-update-categories-modal', ids: $this->getSelected());
    }

    #[On('bulk-delete-categories')]
    public function deleteSelected($ids, $queryParams)
    {
        if (empty($ids)) {
            return;
        }

        DB::transaction(function () use ($ids) {
            Category::whereIn('id', $ids)->delete();
        });

        session()->flash('toast-notification', [
            'message' => 'Selected categories have been deleted successfully.',
            'type' => 'success',
        ]);
        $this->clearSelected();

        $callbackUrl = route('admin.products.categories.index') . ($queryParams ? "?{$queryParams}" : '');
        return $this->redirect($callbackUrl);
    }

    #[On('bulk-update-categories')]
    public function setSelectedFeatured($ids, $queryParams)
    {
        if (empty($ids)) {
            return;
        }

        DB::transaction(function () use ($ids) {
            Category::whereIn('id', $ids)->update(['featured' => true]);
        });

        session()->flash('toast-notification', [
            'message' => 'Selected categories have been featured successfully.',
            'type' => 'success',
        ]);
        $this->clearSelected();

        $callbackUrl = route('admin.products.categories.index') . ($queryParams ? "?{$queryParams}" : '');
        return $this->redirect($callbackUrl);
    }
}
