<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_projects' => $this['total_projects'],
            'active_projects' => $this['active_projects'],
            'total_tasks' => $this['total_tasks'],
            'completed_tasks' => $this['completed_tasks'],
            'pending_tasks' => $this['pending_tasks'],
            'overdue_tasks' => $this['overdue_tasks'],
        ];
    }
}
