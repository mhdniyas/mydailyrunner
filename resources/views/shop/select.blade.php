<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Shop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($shops->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($shops as $shop)
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <form method="POST" action="{{ route('shops.set') }}">
                                        @csrf
                                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                        <div class="flex flex-col items-start">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $shop->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $shop->location }}</p>
                                            <p class="text-xs text-gray-400 mt-1">Role: {{ ucfirst($shop->pivot->role) }}</p>
                                            <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                                Select Shop
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-gray-500">You don't have access to any shops yet.</p>
                            <a href="{{ route('shops.create.first') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Create Your First Shop
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
