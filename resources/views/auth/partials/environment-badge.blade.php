@if (! app()->environment('production'))
    <div class="jm-auth-brand-meta">
        {{ Str::title(app()->environment()) }} {{ __('Environment') }}
    </div>
@endif
