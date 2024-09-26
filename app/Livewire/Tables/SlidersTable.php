<?php

namespace App\Livewire\Tables;

use App\Enums\UserRole;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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
    Header,
    Footer,
    PowerGrid,
    PowerGridFields,
    PowerGridComponent
};

final class SlidersTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'sliders';
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
            Exportable::make('sliders')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
                // ->queues(6)
                // ->onQueue('my-dishes')
                // ->onConnection('redis'),
            Header::make()
                ->showSearchInput()
                ->showToggleColumns(),
                // ->includeViewOnTop('components.datatables.table-header'),
            Footer::make()
                ->showPerPage(perPage: 10, perPageValues: [0, 4, 10, 50, 100, 500])
                ->showRecordCount('full'),
        ];
    }

    public function datasource(): Builder
    {
        return Slider::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title')
            ->add('subtitle')
            ->add('starting_price')
            ->add('is_active')
            ->add('position')
            ->add('url')
            ->add('created_at')
            ->add('updated_at')
            ->add('created_at_formatted', fn(Slider $row) => Carbon::parse($row->created_at)->format('d/m/Y'))
            ->add('updated_at_formatted', fn(Slider $row) => Carbon::parse($row->updated_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),
            Column::make('Title', 'title')
                ->searchable(),
            Column::make('Subtitle', 'subtitle')
                ->searchable(),
            Column::make('Starting Price', 'starting_price')
                ->sortable(),
            Column::make('Active', 'is_active')
                ->toggleable(
                    hasPermission: Auth::user()->role === UserRole::Admin,
                    trueLabel: 'Yes',
                    falseLabel: 'No'
                )
                ->sortable(),
            Column::make('Position', 'position')
                ->sortable(),
            Column::make('Url (Relative)', 'url')
                ->hidden(true, false)
                ->visibleInExport(true),
            Column::make('Created At', 'created_at_formatted', 'created_at'),
            Column::make('Updated At', 'updated_at_formatted', 'updated_at'),
            Column::action('Action')
                ->visibleInExport(false)
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at_formatted', 'created_at'),
            Filter::datepicker('updated_at_formatted', 'updated_at'),
            Filter::boolean('is_active')
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
                ->dispatch('open-bulk-delete-slider-modal', []),
        ];
    }

    public function actions(Slider $row): array
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
                ->dispatchSelf('edit-slider', ['slider' => $row]),
            Button::add('delete')
                ->slot('
                    <span class="inline-flex items-center justify-center gap-2">
                        <i class="ti ti-trash text-2xl text-red-500"></i>
                        Delete
                    </span>
                ')
                ->class('text-slate-500 flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded')
                ->dispatch('open-delete-slider-modal', ['id' => $row->id]),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Slider::query()->find($id)->update([
            $field => e($value),
        ]);

        $this->skipRender();
    }


    #[On('add-slider')]
    public function addSlider(): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.sliders-table", "redirect-to-create-page", { queryParams: queryParams })
        ');
    }

    #[On('edit-slider')]
    public function editSlider(Slider $slider): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.sliders-table", "redirect-to-edit-page", { id: ' . $slider->id . ', queryParams: queryParams })
        ');
    }

    #[On('redirect-to-create-page')]
    public function redirectToCreatePage(string $queryParams = ''): void
    {
        $createPage = route('admin.sliders.create', [], false);
        $callbackUrl = route('admin.sliders.index', [], false) . ($queryParams ? "?{$queryParams}" : '');


        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $createPageWithParams = $createPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($createPageWithParams, true);
    }

    #[On('redirect-to-edit-page')]
    public function redirectToEditPage(int|string $id, string $queryParams = ''): void
    {
        $editPage = route('admin.sliders.edit', ['slider' => $id], false);
        $callbackUrl = route('admin.sliders.index', [], false) . ($queryParams ? "?{$queryParams}" : '');

        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $editPageWithParams = $editPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($editPageWithParams, true);
    }

    #[On('delete-slider')]
    public function delete(string|int $id, string $queryParams = '')
    {
        if ($id) {
            Gate::authorize("delete", Slider::class);

            $slider = Slider::query()->findOrFail($id);
            $callbackUrl = route('admin.sliders.index') . ($queryParams ? "?{$queryParams}" : '');

            try {
                $imagePath = $slider->image;

                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            } catch (\Exception $e) {
                session()->flash('toast-notification', [
                    'type' => 'danger',
                    'message' => "There was an error deleting file for slider. Please try again.",
                ]);

                return $this->redirect($callbackUrl, true);
            }

            $slider->delete();
            session()->flash('toast-notification', [
                'type' => 'danger',
                'message' => "Slider has been deleted!",
            ]);

            return $this->redirect($callbackUrl, true);
        }
    }

    #[On('bulk-delete-slider')]
    public function bulkDelete(string $queryParams = '')
    {
        if ($this->checkboxValues && !empty($this->checkboxValues)) {
            Gate::authorize("delete", Slider::class);

            $sliders = Slider::query()->findMany($this->checkboxValues);
            $callbackUrl = route('admin.sliders.index') . ($queryParams ? "?{$queryParams}" : '');

            foreach($sliders as $slider) {
                try {
                    $imagePath = $slider->image;

                    if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                } catch (\Exception $e) {
                    session()->flash('toast-notification', [
                        'type' => 'danger',
                        'message' => 'There was an error deleting file for slider. Please try again.'
                    ]);

                    return $this->redirect($callbackUrl, true);
                }
            }

            DB::transaction(function () {
                Slider::destroy($this->checkboxValues);
            });

            session()->flash('toast-notification', [
                'type' => 'danger',
                'message' => "Sliders has been deleted!",
            ]);

            $this->js('window.pgBulkActions.clearAll()');
            return $this->redirect($callbackUrl, true);
        }
    }
}
