<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'real_name' => $this->real_name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => $this->role
        ];
    }
}
