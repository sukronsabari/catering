<?php

namespace App\Livewire\Tables;

use App\Enums\UserRole;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\{
    Button,
    Column,
    Exportable,
    Footer,
    Header,
    PowerGrid,
    PowerGridFields,
    PowerGridComponent
};

final class CategoriesTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'categories';
    public bool $deferLoading = true;
    public string $loadingComponent = 'components.loading.datatable-loading';

    protected function queryString(): array
    {
        return $this->powerGridQueryString();
    }

    public function setUp(): array
    {
        $this->showCheckBox();
        // $this->persist(
        //     tableItems: ['columns', 'filters', 'sort'],
        //     prefix: Auth::user()->id,
        // );

        return [
            Exportable::make('categories')
            ->striped()
            ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            // ->queues(6)
            // ->onQueue('my-dishes')
            // ->onConnection('redis'),
            Header::make()
                ->showSearchInput()
                ->showToggleColumns(),
            Footer::make()
                ->showPerPage(perPage: 10, perPageValues: [0, 4, 10, 50, 100, 500])
                ->showRecordCount('full'),
        ];
    }

    public function datasource(): Builder
    {
        return Category::query();
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
            ->add('slug')
            ->add('featured')
            ->add('description')
            ->add('parent_id')
            ->add('parent', fn(Category $row) => optional($row->parent)?->name)
            ->add('image', fn(Category $row) => $row->image ? '<img src="' . asset($row->image) . '">' : 'N/A')
            ->add('icon', fn(Category $row) => $row->icon ? '<img src="' . asset($row->icon) . '">' : 'N/A')
            ->add('created_at')
            ->add('updated_at')
            ->add('created_at_formatted', fn(Category $row) => Carbon::parse($row->created_at)->format('d/m/Y'))
            ->add('updated_at_formatted', fn(Category $row) => Carbon::parse($row->updated_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->searchable(),
            Column::make('Parent', 'parent', 'parent_id')
                ->sortable(),
            Column::make('Slug', 'slug')
                ->sortable()
                ->searchable(),
            Column::make('Featured', 'featured')
                ->toggleable(
                    hasPermission: Auth::user()->role === UserRole::Admin,
                    trueLabel: 'Yes',
                    falseLabel: 'No'
                )
                ->sortable(),
            Column::make('Description', 'description'),
            Column::make('Image', 'image'),
            Column::make('Icon', 'icon'),
            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),
            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at_formatted', 'created_at'),
            Filter::datepicker('updated_at_formatted', 'updated_at'),
            Filter::boolean('featured')
                ->label('yes', 'no'),
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
                ->dispatch('open-bulk-delete-category-modal', []),
        ];
    }

    public function actions(Category $row): array
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
                ->dispatchSelf('edit-category', ['category' => $row]),
            Button::add('delete')
                ->slot('
                    <span class="inline-flex items-center justify-center gap-2">
                        <i class="ti ti-trash text-2xl text-red-500"></i>
                        Delete
                    </span>
                ')
                ->class('text-slate-500 flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded')
                ->dispatch('open-delete-category-modal', ['id' => $row->id]),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Category::query()->find($id)->update([
            $field => e($value),
        ]);

        $this->skipRender();
    }


    #[On('add-category')]
    public function add(): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.categories-table", "redirect-to-create-page", { queryParams: queryParams })
        ');
    }

    #[On('edit-category')]
    public function edit(Category $category): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.categories-table", "redirect-to-edit-page", { id: ' . $category->id . ', queryParams: queryParams })
        ');
    }

    #[On('redirect-to-create-page')]
    public function redirectToCreatePage(string $queryParams = ''): void
    {
        $createPage = route('admin.products.categories.create', [], false);
        $callbackUrl = route('admin.products.categories.index', [], false) . ($queryParams ? "?{$queryParams}" : '');


        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $createPageWithParams = $createPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($createPageWithParams, true);
    }

    #[On('redirect-to-edit-page')]
    public function redirectToEditPage(int|string $id, string $queryParams = ''): void
    {
        $editPage = route('admin.products.categories.edit', ['category' => $id], false);
        $callbackUrl = route('admin.products.categories.index', [], false) . ($queryParams ? "?{$queryParams}" : '');

        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $editPageWithParams = $editPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($editPageWithParams, true);
    }

    #[On('delete-category')]
    public function delete(string|int $id, string $queryParams = '')
    {
        if ($id) {
            Gate::authorize('delete', Category::class);

            $category = Category::query()->findOrFail($id);
            $callbackUrl = route('admin.products.categories.index') . ($queryParams ? "?{$queryParams}" : '');

            try {
                $imagePath = $category->image;
                $iconPath = $category->icon;

                if ($iconPath && Storage::disk('public')->exists($iconPath)) {
                    Storage::disk('public')->delete($iconPath);
                }

                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            } catch (\Exception $e) {
                session()->flash('toast-notification', [
                    'type' => 'danger',
                    'message' => 'There was an error deleting file for category. Please try again.'
                ]);

                return $this->redirect($callbackUrl);
            }

            $category->delete();
            session()->flash('toast-notification', [
                'type' => 'danger',
                'message' => "Category has been deleted!",
            ]);

            return $this->redirect($callbackUrl, true);
        }
    }

    #[On('bulk-delete-category')]
    public function bulkDelete(string $queryParams = '')
    {
        if ($this->checkboxValues && !empty($this->checkboxValues)) {
            Gate::authorize('delete', Category::class);

            $categories = Category::query()->findMany($this->checkboxValues);
            $callbackUrl = route('admin.products.categories.index') . ($queryParams ? "?{$queryParams}" : '');

            foreach($categories as $category) {
                try {
                    $imagePath = $category->image;
                    $iconPath = $category->icon;

                    if ($iconPath && Storage::disk('public')->exists($iconPath)) {
                        Storage::disk('public')->delete($iconPath);
                    }

                    if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with('toast-notification', [
                        'type' => 'error',
                        'message' => 'There was an error deleting file for category. Please try again.'
                    ]);
                }
            }

            DB::transaction(function () {
                Category::destroy($this->checkboxValues);
            });

            session()->flash('toast-notification', [
                'type' => 'danger',
                'message' => "Categories has been deleted!",
            ]);

            $this->js('window.pgBulkActions.clearAll()');
            return $this->redirect($callbackUrl, true);
        }
    }
}
