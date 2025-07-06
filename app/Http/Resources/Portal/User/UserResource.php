<?php

namespace App\Http\Resources\Portal\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'status' => $this->status,
            'status_label' => $this->status ? User::STATUSES[$this->status] : null,
            'type' => $this->type,
            'type_label' => $this->type ? User::TYPES[$this->type] : null,
            'settings' => $this->settings,
            'last_login_at' => $this->last_login_at?->format('Y-m-d H:i:s'),
            'last_ip_address' => $this->last_ip_address,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'tenant' => $this->whenLoaded('tenant', function () {
                return [
                    'id' => $this->tenant->id,
                    'name' => $this->tenant->name,
                    'slug' => $this->tenant->slug,
                ];
            }),
        ];
    }
} 