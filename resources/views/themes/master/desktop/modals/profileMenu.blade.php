<!-- Account Settings Modal -->
<div class="modal-header">
    <h4 class="modal-title">Account</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="w-100 d-flex justify-content-center">
        <img src="{{ __me()->profile_picture }}"
             class="img-fluid img-thumbnail rounded-circle mb-3"
             style="width: 120px; height: 120px;" alt="{{ __me()->full_name }}">
    </div>
    <h3 class="w-100 text-center">{{ __me()->full_name }}</h3>
    <p class="mb-3 text-center">{{ __me()->role }}</p>

    <a href="{{ route('module.user.setting.general') }}" class="btn btn-block btn-light"><i class="fas fa-user-cog"></i> General Settings</a>
    <a href="{{ route('module.user.setting.security') }}" class="btn btn-block btn-light"><i class="fas fa-shield-alt"></i> Security Settings</a>
    <a href="{{ route('module.chat.show') }}" class="btn btn-block btn-light"><i class="fas fa-inbox"></i> Inbox</a>
    <a href="{{ route('auth.logout') }}" class="btn btn-block btn-light"><i class="fas fa-sign-out-alt"></i> Log out</a>
</div>