@extends('admin.partials.master')

@section('master')
<div class="top-header">
    <div class="page-title">
        <h2>Frontend Management</h2>
        <p class="page-subtitle">Customize your landing page, packages, and contact information</p>
    </div>
</div>

<div class="row">
    <!-- Settings Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="mdi mdi-cog text-primary"></i> General Settings
                </h5>

                <form action="{{ route('admin.frontend.updateSettings') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Logo Upload -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Website Logo</label>
                            @if(isset($settings['logo']) && $settings['logo'])
                                <div class="mb-2">
                                    <img src="{{ asset($settings['logo']) }}" alt="Current Logo" style="max-height: 60px; background: white; padding: 10px; border-radius: 8px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <small class="text-muted">Recommended: 200x60px, PNG with transparent background</small>
                        </div>

                        <!-- Favicon Upload -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Favicon</label>
                            @if(isset($settings['favicon']) && $settings['favicon'])
                                <div class="mb-2">
                                    <img src="{{ asset($settings['favicon']) }}" alt="Current Favicon" style="max-height: 32px; background: white; padding: 5px; border-radius: 4px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="favicon" accept="image/x-icon,image/png,image/jpeg">
                            <small class="text-muted">Recommended: 32x32px ICO or PNG</small>
                        </div>

                        <div class="col-md-12">
                            <hr style="border-color: var(--border-color); margin: 30px 0;">
                        </div>

                        <!-- Site Title -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Site Title</label>
                            <input type="text" class="form-control" name="site_title"
                                   value="{{ $settings['site_title'] ?? 'Review Pro' }}" required>
                        </div>

                        <!-- Site Tagline -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Site Tagline</label>
                            <input type="text" class="form-control" name="site_tagline"
                                   value="{{ $settings['site_tagline'] ?? 'Amazon Review Management Bot' }}" required>
                        </div>

                        <!-- Hero Title -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hero Section Title</label>
                            <input type="text" class="form-control" name="hero_title"
                                   value="{{ $settings['hero_title'] ?? 'Automate Amazon Reviews with AI-Powered Precision' }}" required>
                        </div>

                        <!-- Hero Subtitle -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hero Section Subtitle</label>
                            <textarea class="form-control" name="hero_subtitle" rows="2" required>{{ $settings['hero_subtitle'] ?? 'Streamline your Amazon review management with our intelligent automation bot. Save time, boost credibility, and grow your business effortlessly.' }}</textarea>
                        </div>

                        <!-- CTA Text -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Call-to-Action Button Text</label>
                            <input type="text" class="form-control" name="cta_text"
                                   value="{{ $settings['cta_text'] ?? 'Get Started Today' }}" required>
                        </div>

                        <div class="col-md-12">
                            <hr style="border-color: var(--border-color); margin: 30px 0;">
                            <h6 style="color: var(--primary); margin-bottom: 20px;">
                                <i class="mdi mdi-phone"></i> Contact Information
                            </h6>
                        </div>

                        <!-- Contact Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" name="contact_email"
                                   value="{{ $settings['contact_email'] ?? 'support@reviewpro.com' }}" required>
                        </div>

                        <!-- Contact Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" name="contact_phone"
                                   value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+1 234 567 8900">
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">WhatsApp Number (with country code)</label>
                            <input type="text" class="form-control" name="whatsapp_number"
                                   value="{{ $settings['whatsapp_number'] ?? '' }}" placeholder="1234567890">
                            <small class="text-muted">Example: 1234567890 (without + or spaces)</small>
                        </div>

                        <!-- Telegram Username -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telegram Username</label>
                            <input type="text" class="form-control" name="telegram_username"
                                   value="{{ $settings['telegram_username'] ?? '' }}" placeholder="@yourusername">
                            <small class="text-muted">Include @ symbol</small>
                        </div>

                        <!-- Facebook URL -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Facebook Page URL</label>
                            <input type="url" class="form-control" name="facebook_url"
                                   value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Info -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="mdi mdi-information text-info"></i> Quick Actions
                </h5>

                <div class="d-grid gap-3">
                    <a href="{{ route('admin.frontend.packages') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-package-variant"></i> Manage Packages
                    </a>

                    <a href="/" target="_blank" class="btn btn-outline-success">
                        <i class="mdi mdi-eye"></i> View Landing Page
                    </a>

                    <button type="button" class="btn btn-outline-info" onclick="showPreview()">
                        <i class="mdi mdi-information-outline"></i> Contact Setup Guide
                    </button>
                </div>

                <hr style="border-color: var(--border-color); margin: 25px 0;">

                <h6 style="color: var(--dark-text); margin-bottom: 15px;">Current Contact Methods:</h6>

                <div style="font-size: 13px; color: var(--dark-text-muted);">
                    <div class="mb-2">
                        <i class="mdi mdi-email text-primary"></i>
                        <strong>Email:</strong> {{ $settings['contact_email'] ?? 'Not set' }}
                    </div>
                    <div class="mb-2">
                        <i class="mdi mdi-whatsapp text-success"></i>
                        <strong>WhatsApp:</strong> {{ $settings['whatsapp_number'] ?? 'Not set' }}
                    </div>
                    <div class="mb-2">
                        <i class="mdi mdi-telegram text-info"></i>
                        <strong>Telegram:</strong> {{ $settings['telegram_username'] ?? 'Not set' }}
                    </div>
                    <div class="mb-2">
                        <i class="mdi mdi-facebook text-primary"></i>
                        <strong>Facebook:</strong> {{ $settings['facebook_url'] ? 'Set' : 'Not set' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 style="color: var(--warning); margin-bottom: 15px;">
                    <i class="mdi mdi-lightbulb-on-outline"></i> Tips
                </h6>
                <ul style="font-size: 13px; color: var(--dark-text-muted); padding-left: 20px; margin: 0;">
                    <li class="mb-2">Fill in all contact methods for better customer reach</li>
                    <li class="mb-2">WhatsApp number should be without + or spaces</li>
                    <li class="mb-2">Telegram username must include @ symbol</li>
                    <li class="mb-2">Test all links before going live</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Packages Preview -->
<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-package-variant text-primary"></i> Active Packages
            </h5>
            <a href="{{ route('admin.frontend.packages') }}" class="btn btn-sm btn-primary">
                <i class="mdi mdi-plus"></i> Manage Packages
            </a>
        </div>

        @if($packages->count() > 0)
            <div class="row">
                @foreach($packages as $package)
                    <div class="col-md-4 mb-3">
                        <div class="card" style="background: var(--dark-bg); border: 2px solid {{ $package->is_popular ? 'var(--primary)' : 'var(--border-color)' }};">
                            <div class="card-body">
                                @if($package->is_popular)
                                    <span class="badge badge-info mb-2">Most Popular</span>
                                @endif
                                <h5 style="color: var(--dark-text);">{{ $package->name }}</h5>
                                <h3 style="color: var(--primary);">${{ number_format($package->price, 2) }}</h3>
                                <p class="text-muted mb-3">per {{ $package->duration }}</p>

                                @if(is_array($package->features))
                                    <ul style="font-size: 13px; padding-left: 20px;">
                                        @foreach(array_slice($package->features, 0, 3) as $feature)
                                            <li class="mb-1">{{ $feature }}</li>
                                        @endforeach
                                        @if(count($package->features) > 3)
                                            <li class="text-muted">+ {{ count($package->features) - 3 }} more features</li>
                                        @endif
                                    </ul>
                                @endif

                                <span class="badge {{ $package->is_active ? 'badge-success' : 'badge-danger' }} mt-2">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5" style="color: var(--dark-text-muted);">
                <i class="mdi mdi-package-variant" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="mt-3">No packages created yet</p>
                <a href="{{ route('admin.frontend.packages') }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus"></i> Create Your First Package
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Setup Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary mb-3">How Contact Integration Works:</h6>
                <p>When users click on a pricing package, they'll see a popup with all your active contact methods:</p>

                <ul>
                    <li><strong>WhatsApp:</strong> Opens WhatsApp chat with your number pre-filled</li>
                    <li><strong>Telegram:</strong> Opens Telegram chat with your username</li>
                    <li><strong>Email:</strong> Opens email client with your address</li>
                    <li><strong>Facebook:</strong> Opens your Facebook page in a new tab</li>
                </ul>

                <div class="alert alert-info mt-3">
                    <i class="mdi mdi-information"></i>
                    <strong>Pro Tip:</strong> Each contact method will include a pre-filled message mentioning which package the user selected!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showPreview() {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}
</script>
@endsection
