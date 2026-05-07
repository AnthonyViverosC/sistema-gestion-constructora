@props([
    'limit'        => 5,
    'totalCount'   => 0,
    'expandLabel'  => 'Ver todos',
    'collapseLabel'=> 'Ver menos',
])

@php
    $needsToggle = $totalCount > $limit;
    $id = 'collapsible-'.uniqid();
@endphp

<div data-collapsible="{{ $id }}" data-limit="{{ $limit }}">
    <div data-collapsible-content="{{ $id }}">
        {{ $slot }}
    </div>

    @if ($needsToggle)
        <div class="px-6 py-3 border-t border-primary/5 bg-primary/[0.02]">
            <button type="button"
                data-collapsible-toggle="{{ $id }}"
                data-expand-label="{{ $expandLabel }}"
                data-collapse-label="{{ $collapseLabel }}"
                class="w-full text-center text-sm font-semibold text-primary/70 hover:text-primary transition-colors flex items-center justify-center gap-1.5">
                <span data-toggle-text>{{ $expandLabel }} ({{ $totalCount - $limit }} más)</span>
                <svg class="size-4 transition-transform" data-toggle-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    @endif
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-collapsible-toggle]');
            if (!btn) return;

            const id      = btn.dataset.collapsibleToggle;
            const wrapper = document.querySelector('[data-collapsible="'+id+'"]');
            if (!wrapper) return;

            const limit   = parseInt(wrapper.dataset.limit, 10);
            const content = wrapper.querySelector('[data-collapsible-content="'+id+'"]');
            const items   = Array.from(content.children);
            const text    = btn.querySelector('[data-toggle-text]');
            const icon    = btn.querySelector('[data-toggle-icon]');

            const isExpanded = wrapper.dataset.expanded === 'true';
            const newExpanded = !isExpanded;
            wrapper.dataset.expanded = newExpanded ? 'true' : 'false';

            items.forEach((item, idx) => {
                if (idx >= limit) item.classList.toggle('hidden', !newExpanded);
            });

            if (icon) icon.style.transform = newExpanded ? 'rotate(180deg)' : '';
            if (text) {
                text.textContent = newExpanded
                    ? btn.dataset.collapseLabel
                    : `${btn.dataset.expandLabel} (${items.length - limit} más)`;
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-collapsible]').forEach(function (wrapper) {
                const id      = wrapper.dataset.collapsible;
                const limit   = parseInt(wrapper.dataset.limit, 10);
                const content = wrapper.querySelector('[data-collapsible-content="'+id+'"]');
                if (!content) return;
                Array.from(content.children).forEach(function (item, idx) {
                    if (idx >= limit) item.classList.add('hidden');
                });
            });
        });
    </script>
    @endpush
@endonce
