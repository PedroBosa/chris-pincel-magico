<?php

namespace App\Providers;

use App\Models\Agendamento;
use App\Models\Configuracao;
use App\Models\Pagamento;
use App\Models\Promocao;
use App\Models\Servico;
use App\Models\User;
use App\Policies\AgendamentoPolicy;
use App\Policies\ConfiguracaoPolicy;
use App\Policies\PagamentoPolicy;
use App\Policies\PromocaoPolicy;
use App\Policies\ServicoPolicy;
use App\Policies\UserPolicy;
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
        Gate::define('access-admin', static fn (User $user): bool => $user->isAdmin());

        Gate::policy(Servico::class, ServicoPolicy::class);
        Gate::policy(Promocao::class, PromocaoPolicy::class);
        Gate::policy(Agendamento::class, AgendamentoPolicy::class);
        Gate::policy(Pagamento::class, PagamentoPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Configuracao::class, ConfiguracaoPolicy::class);
    }
}
