{{-- App appearance — installation-wide; administrator only --}}
@if(!empty($appSettingsSaved))
    <div id="app-settings-saved-alert" class="alert alert-success mb-3" role="alert">
        <i class="ti ti-check me-1"></i>{{ $LANG['save_success'] ?? 'Saved successfully.' }}
    </div>
    <script>
    (function () {
        var el = document.getElementById('app-settings-saved-alert');
        if (!el) return;
        setTimeout(function () {
            el.style.transition = 'opacity 0.35s ease';
            el.style.opacity = '0';
            setTimeout(function () {
                el.remove();
                try {
                    if (window.history && window.history.replaceState) {
                        var u = new URL(window.location.href);
                        u.searchParams.delete('saved');
                        window.history.replaceState({}, '', u.pathname + u.search + u.hash);
                    }
                } catch (e) {}
            }, 380);
        }, 5000);
    })();
    </script>
@endif

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $LANG['admin_app_appearance'] ?? 'App appearance' }}</h3>
            </div>
            @if(empty($globalApp))
                <div class="card-body">
                    <div class="alert alert-warning mb-0">{{ $LANG['admin_app_settings_table_missing'] ?? 'The settings table is not available. Apply pending SQL patches from System Options.' }}</div>
                </div>
            @else
            <form method="post" action="index.php?module=admin&view=app_settings" accept-charset="utf-8">
                <input type="hidden" name="op" value="save_global_app_settings" />

                <div class="card-body">
                    <p class="text-secondary small mb-3">{{ $LANG['admin_app_appearance_help'] ?? 'These settings apply to all domains (organisation tenants). They control the product name, optional logo, and footer links shown across the application.' }}</p>

                    <h4 class="mb-2">{{ $LANG['admin_app_header_section'] ?? 'Header & identity' }}</h4>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="app_name">{{ $LANG['admin_app_name'] ?? 'Application name' }}</label>
                            <input type="text" class="form-control" name="app_name" id="app_name" value="{{ htmlsafe($globalApp['app_name'] ?? '') }}" maxlength="191" required />
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="app_logo">{{ $LANG['admin_app_logo_url'] ?? 'Logo image URL' }}</label>
                            <input type="text" class="form-control" name="app_logo" id="app_logo" value="{{ htmlsafe($globalApp['app_logo'] ?? '') }}" placeholder="https://… or /images/…" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="app_website">{{ $LANG['admin_app_website'] ?? 'Primary website URL' }}</label>
                            <input type="text" class="form-control" name="app_website" id="app_website" value="{{ htmlsafe($globalApp['app_website'] ?? '') }}" />
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="app_website_label">{{ $LANG['admin_app_website_label'] ?? 'Website link label' }}</label>
                            <input type="text" class="form-control" name="app_website_label" id="app_website_label" value="{{ htmlsafe($globalApp['app_website_label'] ?? '') }}" />
                        </div>
                    </div>

                    <h4 class="mb-2 mt-4">{{ $LANG['admin_app_footer_section'] ?? 'Footer' }}</h4>
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <label class="form-label" for="app_footer_text">{{ $LANG['admin_app_footer_text'] ?? 'Footer thank-you text' }}</label>
                            <input type="text" class="form-control" name="app_footer_text" id="app_footer_text" value="{{ htmlsafe($globalApp['app_footer_text'] ?? '') }}" />
                        </div>
                    </div>

                    @foreach ($footerLinkGroups ?? [] as $fl)
                        <div class="row mb-2 border rounded p-2 bg-light-lt">
                            <div class="col-12 small text-secondary mb-1">{{ $LANG['admin_app_footer_link'] ?? 'Footer link' }} {{ $fl['n'] }}</div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="app_footer_link{{ $fl['n'] }}_label">{{ $LANG['label'] ?? 'Label' }}</label>
                                <input type="text" class="form-control" name="app_footer_link{{ $fl['n'] }}_label" id="app_footer_link{{ $fl['n'] }}_label" value="{{ htmlsafe($fl['label'] ?? '') }}" />
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="app_footer_link{{ $fl['n'] }}_url">{{ $LANG['url'] ?? 'URL' }}</label>
                                <input type="text" class="form-control" name="app_footer_link{{ $fl['n'] }}_url" id="app_footer_link{{ $fl['n'] }}_url" value="{{ htmlsafe($fl['url'] ?? '') }}" />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card-footer">
                    <div class="d-flex">
                        <a href="index.php?module=admin&view=index" class="btn btn-link">{{ $LANG['cancel'] ?? 'Cancel' }}</a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="ti ti-device-floppy me-1"></i>{{ $LANG['save'] ?? 'Save' }}
                        </button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
