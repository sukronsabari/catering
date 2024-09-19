<li>
    <!-- Main Category Item -->
    <div
        class="py-2 px-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
        :class="{ 'bg-blue-100 dark:bg-blue-600': isSelected({{ $category->id }}) }"
        x-on:click="selectCategory({{ $category->id }}, '{{ $category->name }}')"
    >
        <span>{{ $category->name }}</span>
    </div>

    <!-- Subcategories -->
    @if($category->children->isNotEmpty())
    <ul class="ml-4 border-l border-gray-200 pl-4 mt-1">
        @foreach ($category->children as $child)
        @include('components.partials.category-item', ['category' => $child, 'level' => $level + 1])
        @endforeach
    </ul>
    @endif
</li>
