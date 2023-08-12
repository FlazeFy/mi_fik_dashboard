<button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/announcement.png'); ?>"); background-color:#FB5E5B;'
    data-bs-target="#selectTypeModal" data-bs-toggle="modal">
    <h5 class="quick-action-text"><i class="fa-solid fa-plus"></i> {{ __('messages.add_announcement') }}</h5>
    <p class="quick-action-info">{{ __('messages.add_announcement_desc') }}</p>
</button>

@include('system.notification.create')