<button class="btn-quick-action" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/announcement.png'); ?>"); background-color:#FB5E5B;'
    data-bs-target="#selectTypeModal" data-bs-toggle="modal">
    <h5 class="quick-action-text"><i class="fa-solid fa-plus"></i> Add Notification</h5>
    <p class="quick-action-info">This is an announcement where you can send some information to all user, someone, by group, or maybe role.</p>
</button>

@include('system.notification.create')