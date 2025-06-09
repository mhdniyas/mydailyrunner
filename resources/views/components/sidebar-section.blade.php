@props(['title', 'icon', 'route', 'active' => false])

<a href="{{ $route }}" 
   class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ $active ? 'bg-accent-600 text-white' : '' }}">
    <i class="fas fa-{{ $icon }} mr-3"></i>
    {{ $title }}
</a>