<?php

namespace App\Livewire\Tables;

use App\Enums\UserRole;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use phpDocumentor\Reflection\PseudoTypes\True_;
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

final class MerchantsTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'merchants';
    public bool $deferLoading = true;
    public string $loadingComponent = 'components.loading.datatable-loading';
    public bool $showErrorBag = true;
    public array $name;

    protected function queryString(): array
    {
        return $this->powerGridQueryString();
    }

    protected function rules()
    {
        return [
            'name.*' => [
                'string',
                'string',
                'min:3',
                'max:255'
            ],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name.*' => 'Merchant Name',
        ];
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('merchants')
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
                ->params(['keys' => [
                    ['label' => 'Description', 'value' => 'description', 'is_table' => false],
                    ['label' => 'Social Links', 'value' => 'social_links', 'is_table' => false]
                ]]),
        ];
    }

    public function datasource(): Builder
    {
        return Merchant::query()->with('user');
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('owner', fn(Merchant $row) => $row->user->name)
            ->add('is_official')
            ->add('banner_image', fn(Merchant $row) =>  $row->banner_image ? '<img src="' . asset($row->banner_image) . '">' : 'N/A')
            ->add('description')
            ->add('phone')
            ->add('social_links', fn(Merchant $row) => $row->formatted_social_links)
            ->add('created_at')
            ->add('updated_at')
            ->add('created_at_formatted', fn(Merchant $row) => Carbon::parse($row->created_at)->format('d/m/Y'))
            ->add('updated_at_formatted', fn(Merchant $row) => Carbon::parse($row->updated_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Merchant Name', 'name')
                ->editOnClick(
                    hasPermission: Auth::user()->role === UserRole::Admin,
                    fallback: '- empty -'
                )
                ->searchable(),
            Column::make('Owner', 'owner')
                ->searchable(),
            Column::make('Is official', 'is_official')
                ->toggleable(
                    hasPermission: Auth::user()->role === UserRole::Admin,
                    trueLabel: 'Yes',
                    falseLabel: 'No'
                )
                ->sortable(),
            Column::make('Banner image', 'banner_image')
                ->visibleInExport(false),
            Column::make('Description', 'description')
                ->hidden(true, true)
                ->visibleInExport(true),
            Column::make('Phone', 'phone'),
            Column::make('Social links', 'social_links')
                ->hidden(true, true)
                ->visibleInExport(true),
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
            Filter::boolean('is_official')
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
            ->dispatch('open-bulk-delete-merchant-modal', []),
        ];
    }

    public function actions(Merchant $row): array
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
                ->dispatchSelf('edit-merchant', ['merchant' => $row]),
            Button::add('delete')
                ->slot('
                    <span class="inline-flex items-center justify-center gap-2">
                        <i class="ti ti-trash text-2xl text-red-500"></i>
                        Delete
                    </span>
                ')
                ->class('text-slate-500 flex gap-2 hover:text-slate-700 hover:bg-slate-100 font-bold p-1 px-2 rounded')
                ->dispatch('open-delete-merchant-modal', ['id' => $row->id]),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Merchant::query()->find($id)->update([
            $field => e($value),
        ]);

        $this->skipRender();
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        $this->validate();

        Merchant::query()->find($id)->update([
            $field => e($value),
        ]);
    }

    #[On('add-merchant')]
    public function add(): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.merchants-table", "redirect-to-create-page", { queryParams: queryParams })
        ');
    }

    #[On('edit-merchant')]
    public function edit(Merchant $merchant): void
    {
        $this->js('
            let queryParams = decodeURIComponent(new URLSearchParams(window.location.search).toString());
            $wire.dispatchTo("tables.merchants-table", "redirect-to-edit-page", { id: ' . $merchant->id . ', queryParams: queryParams })
        ');
    }

    #[On('redirect-to-create-page')]
    public function redirectToCreatePage(string $queryParams = ''): void
    {
        $createPage = route('admin.merchants.create', [], false);
        $callbackUrl = route('admin.merchants.index', [], false) . ($queryParams ? "?{$queryParams}" : '');


        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $createPageWithParams = $createPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($createPageWithParams, true);
    }

    #[On('redirect-to-edit-page')]
    public function redirectToEditPage(int|string $id, string $queryParams = ''): void
    {
        $editPage = route('admin.merchants.edit', ['merchant' => $id], false);
        $callbackUrl = route('admin.merchants.index', [], false) . ($queryParams ? "?{$queryParams}" : '');

        $encodedCallbackUrl = rawurlencode($callbackUrl);
        $editPageWithParams = $editPage . "?callbackUrl=" . $encodedCallbackUrl;

        $this->redirect($editPageWithParams, true);
    }

    #[On('delete-merchant')]
    public function delete(string|int $id, string $queryParams = '')
    {
        Gate::authorize('delete', Merchant::class);

        if ($id) {
            $merchant = Merchant::query()->findOrFail($id);
            $callbackUrl = route('admin.merchants.index') . ($queryParams ? "?{$queryParams}" : '');

            try {
                $defaultMerchantBanner = env('DEFAULT_MERCHANT_BANNER', 'images/merchants/banners/default.png');
                $bannerImagePath = $merchant->banner_image;

                if ($bannerImagePath !== $defaultMerchantBanner && Storage::disk('public')->exists($bannerImagePath)) {
                    Storage::disk('public')->delete($bannerImagePath);
                }
            } catch (\Exception $e) {
                session()->flash('toast-notification', [
                    'type' => 'danger',
                    'message' => 'There was an error deleting file for category. Please try again.'
                ]);

                return $this->redirect($callbackUrl);
            }

            DB::transaction(function () use ($merchant) {
                $userMerchant = User::findOrFail($merchant->user_id);

                if ($userMerchant->role === UserRole::Merchant) {
                    $userMerchant->update(['role' => UserRole::User]);
                }

                $merchant->delete();
            });

            session()->flash('toast-notification', [
                'type' => 'danger',
                'message' => "Merchant has been deleted!",
            ]);

            return $this->redirect($callbackUrl, true);
        }
    }

    #[On('bulk-delete-merchant')]
    public function bulkDelete(string $queryParams = '')
    {
        Gate::authorize('delete', Merchant::class);

        if ($this->checkboxValues && !empty($this->checkboxValues)) {
            $merchants = Merchant::query()->findMany($this->checkboxValues);
            $callbackUrl = route('admin.merchants.index') . ($queryParams ? "?{$queryParams}" : '');

            foreach($merchants as $merchant) {
                try {
                    $defaultMerchantBanner = env('DEFAULT_MERCHANT_BANNER', 'images/merchants/banners/default.png');
                    $bannerImagePath = $merchant->banner_image;

                    if ($bannerImagePath !== $defaultMerchantBanner && Storage::disk('public')->exists($bannerImagePath)) {
                        Storage::disk('public')->delete($bannerImagePath);
                    }
                } catch (\Exception $e) {
                    session()->flash('toast-notification', [
                        'type' => 'danger',
                        'message' => 'There was an error deleting file for category. Please try again.'
                    ]);

                    return $this->redirect($callbackUrl);
                }
            }

            DB::transaction(function () use ($merchants) {
                foreach($merchants as $merchant) {
                    $userMerchant = User::findOrFail($merchant->user_id);

                    if ($userMerchant->role === UserRole::Merchant) {
                        $userMerchant->update(['role' => UserRole::User]);
                    }

                    $merchant->delete();
                }
            });

            session()->flash('toast-notification', [
                'type' => 'danger',
                'message' => "Merchant has been deleted!",
            ]);

            $this->js('window.pgBulkActions.clearAll()');
            return $this->redirect($callbackUrl, true);
        }
    }
}
