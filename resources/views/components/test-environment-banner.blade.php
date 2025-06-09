<x-app-layout>
    @if(View::shared('testEnvironmentBanner', false))
        <div class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 py-2 text-white bg-red-600">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-medium">Test Environment - Not for Production Use</span>
            </div>
            <div class="text-sm">
                Version: Testing
            </div>
        </div>
    @endif

    {{ $slot }}
</x-app-layout>
