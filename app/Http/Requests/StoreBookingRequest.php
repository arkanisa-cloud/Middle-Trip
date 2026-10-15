<?php

namespace App\Http\Requests;

use App\Models\Expedition;
use App\Rules\ValidEmailAddress;
use App\Rules\ValidNik;
use App\Rules\ValidWhatsAppNumber;
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $user = $this->user();
        if ($user) {
            $merge = [];
            if (! $this->filled('customer_name') && $user->name) {
                $merge['customer_name'] = $user->name;
            }
            if (! $this->filled('customer_email') && $user->email) {
                $merge['customer_email'] = $user->email;
            }
            if (! $this->filled('customer_phone') && $user->phone) {
                $merge['customer_phone'] = $user->phone;
            }
            if (! $this->filled('customer_nik') && $user->nik) {
                $merge['customer_nik'] = $user->nik;
            }
            if (! empty($merge)) {
                $this->merge($merge);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expedition_id' => [
                'nullable',
                'exists:expeditions,id',
                'required_if:trip_type,open',
                'required_without:trip_type',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $exp = Expedition::find($value);
                        if ($exp && $exp->status === 'price_locked') {
                            $fail('Pendaftaran untuk batch ekspedisi ini sudah ditutup karena harga telah dikunci (Price Locked).');
                        } elseif ($exp && $exp->status !== 'open') {
                            $fail('Pendaftaran untuk batch ekspedisi ini sudah tidak aktif atau ditutup.');
                        }
                    }
                },
            ],
            'route_id' => ['required', 'exists:routes,id'],
            'meeting_point_id' => ['nullable', 'exists:meeting_points,id'],
            'trip_type' => ['nullable', 'string', 'in:open,private'],
            'hiking_type' => [
                'nullable',
                'string',
                'in:camping,tektok',
                function ($attribute, $value, $fail) {
                    $tripType = $this->input('trip_type', 'open');
                    $expeditionId = $this->input('expedition_id');
                    if ($tripType !== 'private' && $expeditionId && $value) {
                        $exp = Expedition::find($expeditionId);
                        if ($exp && $exp->hiking_type && $exp->hiking_type !== $value) {
                            $batchTypeLabel = $exp->hiking_type === 'camping' ? 'Camping' : 'Tek-tok';
                            $requestedTypeLabel = $value === 'camping' ? 'Camping' : 'Tek-tok';
                            $fail("Batch jadwal ekspedisi yang dipilih bertipe {$batchTypeLabel}, tidak dapat mendaftar untuk tipe {$requestedTypeLabel}.");
                        }
                    }
                },
            ],
            'departure_date' => ['nullable', 'date', 'after_or_equal:today'],
            'customer_name' => ['required', 'string', 'min:3', 'max:150'],
            'customer_email' => ['required', 'string', 'max:150', new ValidEmailAddress],
            'customer_phone' => ['nullable', 'string', new ValidWhatsAppNumber],
            'customer_nik' => ['nullable', new ValidNik],
            'pax_count' => ['required', 'integer', 'min:1', 'max:10'],
            'participants' => ['required', 'array', 'size:'.$this->input('pax_count', 1)],
            'participants.*.full_name' => ['nullable', 'string', 'max:150'],
            'participants.*.nik' => ['nullable', new ValidNik],
            'participants.*.is_leader' => ['nullable', 'boolean'],
            'addons' => ['nullable', 'array'],
            'addons.*.id' => ['required', 'exists:addons,id'],
            'addons.*.quantity' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
