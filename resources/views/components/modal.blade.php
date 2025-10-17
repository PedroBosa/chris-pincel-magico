<!-- Modal Base Component -->
@props(['name' => null, 'id' => null, 'size' => '2xl', 'maxWidth' => '2xl'])

@php
    $modalId = $name ?? $id ?? 'modal-' . uniqid();
    $maxWidthClass = match($maxWidth ?? $size) {
        'sm' => 'sm',
        'md' => 'md',
        'lg' => 'lg',
        'xl' => 'xl',
        '2xl' => '2xl',
        '3xl' => '3xl',
        '4xl' => '4xl',
        '5xl' => '5xl',
        default => '2xl'
    };
@endphp

<div 
    id="modal-{{ $modalId }}"
    class="modal-overlay fixed inset-0 z-50 overflow-y-auto hidden"
    style="display: none;"
>
    <!-- Backdrop -->
    <div 
        class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm transition-opacity"
        onclick="closeModal('{{ $modalId }}')"
    ></div>

    <!-- Modal Dialog -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div 
            class="relative w-full max-w-{{ $maxWidthClass }} bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all"
            onclick="event.stopPropagation()"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-neutral-200 bg-gradient-to-r from-primary-50 to-white">
                @isset($title)
                    <div class="flex-1">{{ $title }}</div>
                @endisset
                <button 
                    type="button"
                    onclick="closeModal('{{ $modalId }}')"
                    class="w-8 h-8 rounded-lg bg-neutral-100 hover:bg-neutral-200 text-neutral-600 hover:text-neutral-900 transition-colors flex items-center justify-center"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                {{ $slot }}
            </div>

            <!-- Footer (optional) -->
            @isset($footer)
                <div class="flex items-center justify-end gap-3 p-6 border-t border-neutral-200 bg-neutral-50">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>

<script>
function openModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) {
        modal.style.display = 'block';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});
</script>


