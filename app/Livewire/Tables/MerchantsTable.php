<?php

namespace App\Livewire\Tables;

use App\Models\Merchant;
use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class MerchantsTable extends DataTableComponent
{
    protected $model = Merchant::class;

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
                    'text' => 'Add Merchant',
                    'id' => 'add-button-before-toolbar',
                    'href' => route('admin.merchants.create'),
                ]
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Owner Name", "user.name")
                ->searchable(),
            Column::make("Name", "name")
                ->searchable(),
            BooleanColumn::make("Is official", "is_official")
                ->sortable(),
            Column::make("Banner image", "banner_image")
                ->collapseAlways(),
            Column::make("Description", "description")
                ->collapseAlways(),
            Column::make("Phone", "phone"),
            Column::make("Social links", "social_links")
                ->format(fn($value, $row) => $row->formatted_social_links)
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
                                'admin.merchants.edit',
                                [
                                    'merchant' => $row,
                                    'callbackUrl' => route('admin.merchants.index', [], false)
                                ]
                            ),
                            'deleteLink' => route(
                                'admin.merchants.destroy',
                                [
                                    'merchant' => $row,
                                    'callbackUrl' => route('admin.merchants.index', [], false)
                                ]
                            ),
                        ]
                    )
                ),
        ];
    }
}
