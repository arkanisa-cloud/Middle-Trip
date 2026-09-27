<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expedition_id' => ['required', 'exists:expeditions,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'meeting_point_id' => ['nullable', 'exists:meeting_points,id'],
            'trip_type' => ['nullable', 'string', 'in:open,private'],
            'customer_name' => ['required', 'string', 'min:3', 'max:150'],
            'customer_email' => ['required', 'email', 'max:150'],
            'customer_phone' => ['required', 'string', 'min:9', 'max:25'],
            'customer_nik' => ['required', 'digits:16'],
            'pax_count' => ['required', 'integer', 'min:1', 'max:10'],
            'participants' => ['required', 'array', 'size:'.$this->input('pax_count', 1)],
            'participants.*.full_name' => ['required', 'string', 'min:3', 'max:150'],
            'participants.*.nik' => ['required', 'digits:16'],
            'participants.*.is_leader' => ['nullable', 'boolean'],
            'addons' => ['nullable', 'array'],
            'addons.*.id' => ['required', 'exists:addons,id'],
            'addons.*.quantity' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
