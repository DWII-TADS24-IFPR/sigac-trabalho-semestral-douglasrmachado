<?php

namespace App\Providers;

use App\Models\CertificateRequest;
use App\Policies\CertificateRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        CertificateRequest::class => CertificateRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-certificate', [CertificateRequestPolicy::class, 'view']);
        Gate::define('update-certificate', [CertificateRequestPolicy::class, 'update']);
        Gate::define('download-certificate', [CertificateRequestPolicy::class, 'download']);
    }
} 