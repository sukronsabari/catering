@extends("layouts.base")

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-800">
    <x-navigation.index />
    <div class="flex overflow-hidden">

        <x-sidebar.index />
        <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

        <main class="relative w-full h-full min-h-screen overflow-y-auto overflow-x-hidden bg-gray-50 pt-[96px] lg:ml-64 dark:bg-gray-900">
            {{ $slot }}
        </main>
    </div>
</div>
@endsection
