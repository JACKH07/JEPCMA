<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\BuildDashboardOverview;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the multi-module dashboard.
     */
    public function __invoke(BuildDashboardOverview $overview): Response
    {
        return Inertia::render('Dashboard', $overview->handle());
    }
}
