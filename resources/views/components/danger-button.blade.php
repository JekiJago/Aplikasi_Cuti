<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-rose-600 text-white font-medium rounded-lg hover:bg-rose-700 transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500']) }}>
    {{ $slot }}
</button>
