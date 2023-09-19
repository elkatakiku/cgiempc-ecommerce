<?php

namespace App\Http\Requests;

use App\Models\User;

class UserStoreRequest extends UserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }
}
