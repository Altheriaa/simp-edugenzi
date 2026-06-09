<?php

namespace App\Providers;

use App\Models\Penilaian;
use App\Models\Proyek;
use App\Models\Sertifikat;
use App\Policies\PenilaianPolicy;
use App\Policies\ProyekPolicy;
use App\Policies\SertifikatPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Proyek::class, ProyekPolicy::class);
        Gate::policy(Penilaian::class, PenilaianPolicy::class);
        Gate::policy(Sertifikat::class, SertifikatPolicy::class);
    }
}
