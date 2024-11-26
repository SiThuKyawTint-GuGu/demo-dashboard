<?php

namespace App\Providers;

use App\Contracts\Auth\LoginInterface;
use App\Contracts\Auth\RegisterInterface;
use App\Contracts\Auth\ResetPasswordInterface;
use App\Contracts\Auth\SendPasswordResetEmailInterface;
use App\Contracts\ExportImport\DatabaseExportImportInterface;
use App\Contracts\ExportImport\ExportImportServiceInterface;
use App\Contracts\MFA\MfaInterface;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegisterService;
use App\Services\Auth\ResetPasswordService;
use App\Services\Auth\SendPasswordResetEmailService;
use App\Services\Excel\DatabaseExportImportService;
use App\Services\Excel\ExportImportService;
use App\Services\MFA\MfaService;
use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind interfaces
        $this->app->bind(LoginInterface::class, LoginService::class);
        $this->app->bind(MfaInterface::class, MfaService::class);
        $this->app->bind(RegisterInterface::class, RegisterService::class);
        $this->app->bind(ResetPasswordInterface::class, ResetPasswordService::class);
        $this->app->bind(SendPasswordResetEmailInterface::class, SendPasswordResetEmailService::class);
        $this->app->bind(DatabaseExportImportInterface::class, DatabaseExportImportService::class);
        $this->app->bind(ExportImportServiceInterface::class, ExportImportService::class);

        $this->app->singleton(DynamoDbClient::class, function ($app) {
            return new DynamoDbClient([
                'region' => config('aws.region'),
                'version' => 'latest',
                'credentials' => [
                    'key'    => config('aws.credentials.key'),
                    'secret' => config('aws.credentials.secret'),
                ],
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Additional bootstrapping if necessary
    }
}
