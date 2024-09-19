<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Product;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Illuminate\Support\Number;

use function PHPUnit\Framework\isEmpty;

class ProductsTable extends DataTableComponent
{
    protected $model = Product::class;

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
            ])
            ->setAdditionalSelects(['has_variation']);

        $this->setSearchDebounce(400);
        $this->setSearchPlaceholder('Search for product...');

        $this->setConfigurableAreas([
            'before-toolbar' => [
                'components.datatables.link',
                [
                    'withIcon' => true,
                    'icon' => 'ti ti-plus',
                    'text' => 'Add Product',
                    'id' => 'add-button-before-toolbar',
                    'href' => route('admin.products.create'),
                ]
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make('Image')
                ->label(
                    function ($row, Column $column) {
                        $mainImage = $row->images->where('is_main', true)->first();

                        if ($mainImage) {
                            return '<img src="' . asset('storage/' . $mainImage->image) . '" alt="Main Image" class="h-20 object-cover rounded">';
                        }

                        return 'No Image';
                    }
                )->html(),
            Column::make("Name", "name")
                ->searchable(),
            Column::make("Merchant", "merchant.name")
                ->searchable(),
            Column::make("Category", "category.name")
                ->searchable(),
            Column::make("Price (IDR)", "price")
                ->format(function ($value, $row, Column $column) {
                    if (!$row->has_variation) {
                        return floatval($row->price);
                    }

                    $product = Product::with('skus')->find($row->id);

                    if (!$product) {
                        return 'N/A';
                    }

                    if ($product->skus->isEmpty()) {
                        return 'No product variation available';
                    }

                    $minPrice = $product->skus->min('price');
                    $maxPrice = $product->skus->max('price');

                    if ($minPrice === $maxPrice) {
                        return $minPrice;
                    }

                    return $minPrice . ' - ' . $maxPrice;
                })
                ->sortable(),
            Column::make("Stock", "stock")
                // ->format(
                //     fn($value, $row, Column $column) =>
                // )
                ->sortable(),
            Column::make("Description", "description")
                ->deselected(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            Column::make('Product Variation')
                ->label(
                    function ($row, Column $column) {
                        if (!$row->has_variation) {
                            return 'No product variation available';
                        }

                        $product = Product::with(['skus.attributes'])->find($row->id);

                        if (!$product) {
                            return 'N/A';
                        }

                        if ($product->skus->isEmpty()) {
                            return 'No product variation available';
                        }

                        return view('components.datatables.variant-table')->with([
                            'product' => $product,
                        ]);
                    }
                )->collapseAlways(),
            Column::make('Action')
                ->label(
                    fn($row, Column $column) => view('components.datatables.action-column')->with(
                        [
                            'editLink' => route('admin.products.edit', [
                                'product' => $row,
                                'callbackUrl' => route('admin.products.index'),
                            ]),
                            'deleteLink' => route(
                                'admin.products.destroy',
                                [
                                    'product' => $row,
                                    'callbackUrl' => route('admin.products.index', [], false)
                                ]
                            ),
                        ]
                    )
                ),
        ];
    }
}
