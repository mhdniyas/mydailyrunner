<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center bg-red-600 text-white font-semibold py-2.5 px-6 sm:py-3 sm:px-8 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 text-sm sm:text-base']) }}>
    {{ $slot }}
</button>
