<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(public DashboardService $dashboardService) {}

    public function index()
    {
        $data = $this->dashboardService->index(auth()->user());

        return new DashboardResource($data);
    }
}
