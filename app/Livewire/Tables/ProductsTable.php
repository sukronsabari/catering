<?php

namespace App\Livewire\Tables;

use App\Enums\UserRole;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ProductsTable extends PowerGridComponent
{
    use WithExport;

    public bool $deferLoading = true;
    public string $loadingComponent = 'components.loading.datatable-loading';


    public function setUp(): array
    {
        $this->showCheckBox();
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: Auth::user()->id,
        );

        return [
            Exportable::make('products')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showSearchInput()
                ->showToggleColumns(),
            Footer::make()
                ->showPerPage(perPage: 10, perPageValues: [0, 4, 10, 50, 100, 500])
                ->showRecordCount('full'),
            Detail::make()
                ->view('components.datatables.detail-column')
                ->showCollapseIcon()
                ->params([
                    'keys' => [
                        ['label' => 'Currency Code', 'value' => 'currency_code', 'is_table' => false],
                        [
                            'label' => 'Product Variations',
                            'value' => 'skus',
                            'is_table' => true,
                            'component' => 'components.datatables.variant-product-table' // Path ke component table
                        ]
                    ]
                ]),
        ];
    }

    public function datasource(): Builder
    {
        return Product::query()
            ->with(['merchant', 'category', 'images', 'productAttributes', 'skus']);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('product', function(Product $row) {
                $mainImage = $row->images->where('is_main', true)->first();
                $imageUrl = $mainImage ? asset('storage/' . $mainImage->image) : asset('storage/images/default/image.png');

                $productName = e($row->name);
                $productSku = e($row->sku ?? '-');

                return "
                    <div class='flex gap-3 w-full'>
                        <div class='flex-1 w-14 h-14'>
                            <img src='{$imageUrl}' alt='Main Image' class='w-full h-full object-cover object-center rounded'>
                        </div>
                        <div>
                            <p class='font-medium'>{$productName}</p>
                            <p class='text-gray-400'>SKU: {$productSku}</p>
                        </div>
                    </div>
                ";
            })
            ->add('owner', fn(Product $row) => $row->merchant->name)
            ->add('is_active')
            ->add('price', function(Product $row) {
                if (!$row->has_variation || $row->skus->isEmpty()) {
                    return Number::currency($row->price, 'IDR', 'id_ID');
                }

                $minPrice = Number::currency($row->skus->min('price'), 'IDR', 'id_ID');
                $maxPrice = Number::currency($row->skus->max('price'), 'IDR', 'id_ID');

                if ($minPrice === $maxPrice) {
                    return $minPrice;
                }

                return "{$minPrice} - {$maxPrice}";
            })
            ->add('stock', function(Product $row) {
                if (!$row->has_variation || $row->skus->isEmpty()) {
                   return $row->stock;
                }

                $totalStock = $row->skus->sum('stock');
                return $totalStock;
            })
            ->add('weight', function(Product $row) {
                if (!$row->has_variation || $row->skus->isEmpty()) {
                    return (int) $row->weight;
                }

                $minWeight = (int) $row->skus->min('weight');
                $maxWeight = (int) $row->skus->max('weight');

                if ($minWeight === $maxWeight) {
                    return (int) $minWeight;
                }

                return "{$minWeight} - {$maxWeight}";
            })
            ->add('has_variation', fn(Product $row) => $row->has_variation ? 'Yes' : 'No')
            ->add('created_at')
            ->add('created_at_formatted', fn(Product $row) => Carbon::parse($row->created_at)->format('d/m/Y'))
            ->add('updated_at_formatted', fn(Product $row) => fn(Product $row) => Carbon::parse($row->updated_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Product', 'product', 'name')
                ->searchable(),
            Column::make('Owner', 'owner'),
            Column::make('Status', 'is_active')
                ->toggleable(
                    hasPermission: Auth::user()->role === UserRole::Admin,
                    trueLabel: 'Yes',
                    falseLabel: 'No'
                )
                ->sortable(),
            Column::make('Price', 'price')
                ->sortable(),
            Column::make('Stock', 'stock')
                ->sortable(),
            Column::make('Has variation', 'has_variation'),
            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::datepicker('created_at_formatted', 'created_at'),
            Filter::boolean('is_active')
                ->label('Yes', 'No'),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('bulk-delete')
                ->class("md:mr-8 focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-primary-600 flex rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 text-gray-600 ring-gray-300 dark:bg-pg-primary-800 bg-white dark:placeholder-pg-primary-400 rounded-md border-0 bg-transparent py-2 px-3 ring-0 placeholder:text-gray-400 focus:outline-none sm:text-sm sm:leading-6 w-auto")
                ->slot('
                        <div class="flex">
                            <div class="h-5 w-5 inline-flex justify-center items-center text-pg-primary-500 dark:text-pg-primary-300">
                                <i class="ti ti-trash text-xl"></i>
                            </div>
                        </div>
                    ')
                ->tooltip('Bulk Delete')
                ->dispatch('open-bulk-delete-product-modal', []),
        ];
    }

    public function actions(Product $row): array
    {
        return [
            Button::add('edit')
                ->slot('
                        <span class="inline-flex items-center justify-center gap-2">
                            <i class="ti ti-edit text-2xl text-blue-500"></i>
                            Edit
                        </span>
                    ')
                ->class('text-slate-500 flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded')
                ->dispatchSelf('edit-product', ['product' => $row]),
            Button::add('delete')
                ->slot('
                        <span class="inline-flex items-center justify-center gap-2">
                            <i class="ti ti-trash text-2xl text-red-500"></i>
                            Delete
                        </span>
                    ')
                ->class('text-slate-500 flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded')
                ->dispatch('open-delete-product-modal', ['id' => $row->id]),
        ];
    }

    public function beforeSearch(string $field = null, string $search = null)
    {
        if ($field === 'name') {
           dd("NAME");
        }

        return $search;
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Product::query()->find($id)->update([
            $field => e($value),
        ]);

        $this->skipRender();
    }

    #[On('add-product')]
    public function add(): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.products-table", "redirect-to-create-page", { queryParams: queryParams })
        ');
    }

    #[On('edit-product')]
    public function edit(Product $product): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.products-table", "redirect-to-edit-page", { id: ' . $product->id . ', queryParams: queryParams })
        ');
    }

    #[On('redirect-to-create-page')]
    public function redirectToCreatePage(string $queryParams = ''): void
    {
        $createPage = route('admin.products.create', [], false);
        $callbackUrl = route('admin.products.index', [], false) . ($queryParams ? "?{$queryParams}" : '');


        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $createPageWithParams = $createPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($createPageWithParams, true);
    }

    #[On('redirect-to-edit-page')]
    public function redirectToEditPage(int|string $id, string $queryParams = ''): void
    {
        $editPage = route('admin.products.edit', ['product' => $id], false);
        $callbackUrl = route('admin.products.index', [], false) . ($queryParams ? "?{$queryParams}" : '');

        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $editPageWithParams = $editPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($editPageWithParams, true);
    }
}
