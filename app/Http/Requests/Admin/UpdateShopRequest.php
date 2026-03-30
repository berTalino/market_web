<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $shop = $this->route('shop');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('shops', 'slug')->ignore($shop)],
            'description' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
