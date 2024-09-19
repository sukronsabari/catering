@extends('layouts.base')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900">
    <x-navigation.index />

    <!-- Page Content -->
    <main {{ $attributes->merge(["class" => "max-w-screen-xl px-2 sm:px-12 mx-auto pt-28 pb-14"]) }}>
        {{ $slot }}
    </main>
    <x-footer />
</div>
@endsection
