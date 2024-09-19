<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Variant</th>
                <th scope="col" class="px-6 py-3">IsDefault</th>
                <th scope="col" class="px-6 py-3">Price</th>
                <th scope="col" class="px-6 py-3">Stock</th>
                <th scope="col" class="px-6 py-3">Weight</th>
                <th scope="col" class="px-6 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->skus as $sku)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td class="px-6 py-4">
                    {{-- Concatenate the attributes, e.g., 'Black - L' --}}
                    @foreach($sku->attributes as $attribute)
                    {{ $attribute->pivot->value }}{{ !$loop->last ? ' - ' : '' }}
                    @endforeach
                </td>
                {{-- @dump($sku->is_default) --}}
                <td class="px-6 py-4">
                    {{ $sku->is_default ? 'True' : 'False' }}
                </td>
                <td class="px-6 py-4">
                    {{ Illuminate\Support\Number::currency($sku->price, in: 'IDR', locale: 'id') }}
                </td>
                <td class="px-6 py-4">
                    {{ $sku->stock }}
                </td>
                <td class="px-6 py-4">
                    {{ (int) $sku->weight }} Gram
                </td>
                <td class="px-6 py-4">
                    {{ $sku->is_active ? 'Active' : 'Inactive' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
