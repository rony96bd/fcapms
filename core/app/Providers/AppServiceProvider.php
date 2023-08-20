<?php

namespace App\Providers;

use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Donor;
use App\Models\Agent;
use App\Models\Page;
use App\Models\SupportTicket;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['request']->server->set('HTTPS', true);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $activeTemplate = activeTemplate();
        $general = GeneralSetting::first();
        $viewShare['general'] = $general;
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language'] = Language::all();
        $viewShare['pages'] = Page::where('tempname', $activeTemplate)->where('slug', '!=', 'home')->get();
        view()->share($viewShare);

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'pending_ticket_count' => SupportTicket::whereIN('status', [0, 2])->count(),
                'pending_donor_count' => Donor::where('status', 0)->count(),
                'pending_agent_count' => Agent::where('status', 0)->count(),
            ]);
        });

        view()->composer('user.partials.sidenav', function ($view) {
            $ids = json_decode(Auth::guard('user')->user()->manage_agent_id);
            $view->with([
                'pending_donor_count' => Donor::where('status', 0)->whereIn('agent_id', $ids)->count(),
                'pending_agent_count' => Agent::where('status', 0)->count(),
            ]);
        });

        view()->composer('agent.partials.sidenav', function ($view) {
            $aid = auth()->guard('agent')->user()->id;
            $view->with([
                'pending_ticket_count' => SupportTicket::whereIN('status', [0, 2])->count(),
                'pending_stuent_count' => Donor::where('status', 0)->where('agent_id', $aid)->count(),

            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if ($general->force_ssl) {
            \URL::forceScheme('https');
        }
        Paginator::useBootstrap();
    }
}
