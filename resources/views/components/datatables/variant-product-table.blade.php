@if ($row->has_variation == "No")
    <p>No Product Variation</p>
@else
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Variant</th>
                    <th scope="col" class="px-6 py-3">Price</th>
                    <th scope="col" class="px-6 py-3">Stock</th>
                    <th scope="col" class="px-6 py-3">Weight (Gram)</th>
                    <th scope="col" class="px-6 py-3">SKU</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Default</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($row['skus'] as $sku)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 capitalize">
                            @foreach ($sku->productAttributes as $attribute)
                                {{ $attribute->pivot->value }}{{ !$loop->last ? ' - ' : '' }}
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            {{ Illuminate\Support\Number::currency($sku->price, in: 'IDR', locale: 'id') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $sku->stock }}
                        </td>
                        {{-- {{ auth()->role=== User }} --}}
                        <td class="px-6 py-4">
                            {{ (int) $sku->weight }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $sku->sku ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <livewire:sku-toggleable-switch :sku="$sku" wire:key="sku-toggleable-switch-{{ $sku->id }}" />
                        </td>
                        <td class="px-6 py-4">
                            <livewire:sku-toggleable-default :sku="$sku" wire:key="sku-default-toggle-{{ $sku->id }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
