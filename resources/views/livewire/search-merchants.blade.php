<div wire:ignore>
    <select
        name="merchant_id"
        x-data
        x-init="
            new TomSelect('#select-merchant', {
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                preload: true,
                options: {{ $selectedMerchant ? json_encode([$selectedMerchant]) : json_encode($recentMerchants) }},
                items: {{ $selectedMerchant ? json_encode([$selectedMerchant->id]) : '[]' }},
                load: function (query, callback) {
                    if (!query.length) return callback();

                    $wire.getMerchants(query).then((result) => {
                        callback(result);
                    }).catch((error) => {
                        callback();
                    })
                },
                render: {
                    option: function(item, escape) {
                        return `<div>${escape(item.name)}</div>`;
                    },
                    item: function(item, escape) {
                        return `<div>${escape(item.name)}</div>`;
                    }
                },
            });
        "
        id="select-merchant"
        placeholder="Select a merchant or search for more...."
        class="bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-green-400 focus:border-green-400 block
        w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500
        dark:focus:border-green-500"
    ></select>
</div>
