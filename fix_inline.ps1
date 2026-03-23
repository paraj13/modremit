$base = "d:\pajju's projects\modremit\resources\views"

function Rep([string]$path, [string]$old, [string]$new) {
    $f = Join-Path $base $path
    if (!(Test-Path $f)) { Write-Host "SKIP (not found): $path"; return }
    $c = [System.IO.File]::ReadAllText($f)
    if ($c.Contains($old)) {
        $c = $c.Replace($old, $new)
        [System.IO.File]::WriteAllText($f, $c)
        Write-Host "OK: $path"
    }
}

# layouts/main.blade.php
Rep 'layouts\main.blade.php' 'style="opacity: 0.05;"' 'class="hr-subtle"'
Rep 'layouts\main.blade.php' 'style="margin:0;"' 'class="nav-btn-reset"'
Rep 'layouts\main.blade.php' 'style="letter-spacing: -1px;"' 'class="page-heading"'
Rep 'layouts\main.blade.php' 'style="width: 44px; height: 44px;"' 'class="user-avatar-circle"'
Rep 'layouts\main.blade.php' 'style="font-size: 0.75rem;"' 'class="user-info-text"'

# layouts/customer.blade.php
Rep 'layouts\customer.blade.php' 'style="position: absolute; bottom: 0; width: 100%;"' 'class="sidebar-footer"'
Rep 'layouts\customer.blade.php' 'style="opacity: 0.05;"' 'class="hr-subtle"'
Rep 'layouts\customer.blade.php' 'style="margin:0;"' 'class="nav-btn-reset"'
Rep 'layouts\customer.blade.php' 'style="letter-spacing: -1px;"' 'class="page-heading"'
Rep 'layouts\customer.blade.php' 'style="width: 44px; height: 44px;"' 'class="user-avatar-circle"'
Rep 'layouts\customer.blade.php' 'style="font-size: 0.7rem;"' 'class="user-info-text"'
Rep 'layouts\customer.blade.php' 'style="font-size:0.6rem;"' 'class="user-kyc-badge"'

# layouts/guest.blade.php
Rep 'layouts\guest.blade.php' 'style="width: 48px; height: 48px;">' 'class="icon-circle-lg">'
Rep 'layouts\guest.blade.php' 'style="font-size: 1.4rem;"' 'class="brand-icon"'
Rep 'layouts\guest.blade.php' 'style="font-size: 1.5rem; letter-spacing: -0.5px;"' 'class="brand-logo-text"'

# track.blade.php
Rep 'track.blade.php' 'style="border-color: var(--brand-lime) !important;"' 'class="border-accent-lime"'

# welcome.blade.php
Rep 'welcome.blade.php' 'style="border-radius:3px;"' 'class="flag-badge-sm"'
Rep 'welcome.blade.php' 'style="width: 140px;">' 'class="converter-sparkline">'
Rep 'welcome.blade.php' 'style="border-color: var(--brand-lime) !important;"' 'class="converter-step-border"'
Rep 'welcome.blade.php' 'style="margin-left: -1px;"' 'class="converter-step-dot"'
Rep 'welcome.blade.php' 'style="width: 24px; height: 24px;"' 'class="icon-circle-xs"'
Rep 'welcome.blade.php' 'style="background-color: var(--brand-lime);"' 'class="card-premium-lime"'
Rep 'welcome.blade.php' 'style="width: 64px; height: 64px;"' 'class="icon-circle-feature"'
Rep 'welcome.blade.php' 'style="background: var(--white-home);"' 'class="card-premium-home"'
Rep 'welcome.blade.php' 'style="background-color: var(--brand-lime);">' 'class="section-accent">'
Rep 'welcome.blade.php' 'style="background: var(--brand-dark); color: var(--brand-lime);"' 'class="section-tag-dark"'
Rep 'welcome.blade.php' 'style="width: 48px; height: 48px;">' 'class="icon-circle-lg">'
Rep 'welcome.blade.php' 'style="font-size: 1.4rem;"' 'class="brand-icon"'
Rep 'welcome.blade.php' 'style="font-size: 1.5rem; letter-spacing: -0.5px;"' 'class="brand-logo-text"'

# admin
Rep 'admin\dashboard.blade.php' 'style="width:140px;">' 'class="chart-sparkline-lg">'
Rep 'admin\dashboard.blade.php' 'style="width:110px;">' 'class="chart-sparkline-md">'
Rep 'admin\dashboard.blade.php' 'style="min-height: 300px;"' 'class="chart-container"'
Rep 'admin\dashboard.blade.php' 'style="border-left: 4px solid var(--brand-dark) !important;"' 'class="card-accent-dark"'
Rep 'admin\dashboard.blade.php' 'style="border-left: 4px solid var(--brand-lime) !important;"' 'class="card-accent-lime"'
Rep 'admin\dashboard.blade.php' 'style="border-left: 4px solid var(--brand-yellow) !important;"' 'class="card-accent-lime"'
Rep 'admin\dashboard.blade.php' 'style="font-size: 0.7rem;"' 'class="user-info-text"'
Rep 'admin\wallets\index.blade.php' 'style="width: 40px; height: 40px;"' 'class="icon-circle-md"'
Rep 'admin\wallets\credit.blade.php' 'style="width: 50px; height: 50px;"' 'class="icon-circle-lg"'
Rep 'admin\recipients\show.blade.php' 'style="width: 80px; height: 80px;"' 'class="icon-avatar-xl"'
Rep 'admin\customers\show.blade.php' 'style="width: 80px; height: 80px;"' 'class="icon-avatar-xl"'
Rep 'admin\transactions\index.blade.php' 'style="min-width: 280px;"' 'class="search-input"'
Rep 'admin\customers\index.blade.php' 'style="min-width: 280px;"' 'class="search-input"'
Rep 'admin\recipients\index.blade.php' 'style="min-width: 280px;"' 'class="search-input"'
Rep 'admin\agents\index.blade.php' 'style="min-width: 280px;"' 'class="search-input"'

# agent
Rep 'agent\dashboard.blade.php' 'style="width:140px;">' 'class="chart-sparkline-lg">'
Rep 'agent\dashboard.blade.php' 'style="width:110px;">' 'class="chart-sparkline-md">'
Rep 'agent\dashboard.blade.php' 'style="min-height: 300px;"' 'class="chart-container"'
Rep 'agent\customers\show.blade.php' 'style="width: 80px; height: 80px;"' 'class="icon-avatar-xl"'
Rep 'agent\customers\create.blade.php' 'style="letter-spacing: -1px;"' ''
Rep 'agent\customers\create.blade.php' 'style="letter-spacing: 1px;"' ''
Rep 'agent\customers\edit.blade.php' 'style="letter-spacing: -1px;"' ''
Rep 'agent\customers\edit.blade.php' 'style="letter-spacing: 1px;"' ''
Rep 'agent\wallet\index.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'agent\wallet\index.blade.php' 'style="font-size: 1.4rem;"' ''
Rep 'agent\recipients\create.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'agent\recipients\edit.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'agent\transfers\create.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'agent\transfers\create.blade.php' 'style="width: 32px; height: 32px; border-radius: 50%;"' 'class="step-badge"'
Rep 'agent\transfers\create.blade.php' 'style="max-width: 120px;"' 'class="currency-select"'

# customer
Rep 'customer\transfers\create.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'customer\transfers\create.blade.php' 'style="width: 32px; height: 32px; border-radius: 50%;"' 'class="step-badge"'
Rep 'customer\transfers\create.blade.php' 'style="max-width: 120px;"' 'class="currency-select"'
Rep 'customer\recipients\create.blade.php' 'style="width: 40px; height: 40px;"' 'class="icon-circle-md"'
Rep 'customer\recipients\create.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'customer\recipients\edit.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'customer\recipients\show.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'customer\transactions\show.blade.php' 'style="width: 48px; height: 48px;"' 'class="icon-circle-lg"'
Rep 'customer\dashboard.blade.php' 'style="background: rgba(255,255,255,0.1); border: none;"' 'class="btn-glass"'
Rep 'customer\auth\register.blade.php' 'style="max-width: 500px;"' 'class="auth-card-sm"'

Write-Host "ALL DONE"
