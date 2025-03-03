<x-pulse class="pulse-container">
    <livewire:pulse.servers cols="full" />

    <livewire:pulse.usage cols="4" rows="2" />

    <livewire:pulse.queues cols="4" />

    <livewire:pulse.cache cols="4" />

    <livewire:pulse.slow-queries cols="8" />

    <livewire:pulse.exceptions cols="6" />

    <livewire:pulse.slow-requests cols="6" />

    <livewire:pulse.slow-outgoing-requests cols="6" />

    <livewire:pulse.slow-jobs cols="6" />

    <style>
        /* Pulse loads css that breaks the navbar position causing overflow issues */
        @media (min-width: 1024px) {
            .fi-sidebar {
                position: sticky;
            }

            nav .fi-icon-btn-icon {
                display: none;
            }
        }
    </style>
</x-pulse>
