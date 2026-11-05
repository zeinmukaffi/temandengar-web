<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alias' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'nullable',
                'required_without:phone',
                'email',
                'max:150',
            ],

            'phone' => [
                'nullable',
                'required_without:email',
                'regex:/^[0-9+\-\s()]{8,20}$/',
            ],

            'topic' => [
                'required',
                'in:academic,personal,social,organization,other',
            ],

            'description' => [
                'required',
                'string',
                'min:10',
                'max:3000',
            ],

            'preferred_method' => [
                'required',
                'in:online,offline',
            ],

            'preferred_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'preferred_time' => [
                'nullable',
                'in:08:00-10:00,10:00-12:00,13:00-15:00,15:00-17:00',
            ],

            'consent' => [
                'accepted',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'alias.required' => 'Nama panggilan wajib diisi.',
            'email.required_without' => 'Email atau WhatsApp harus diisi.',
            'phone.required_without' => 'WhatsApp atau email harus diisi.',
            'topic.required' => 'Silakan pilih topik konsultasi.',
            'description.required' => 'Cerita atau kebutuhan konsultasi wajib diisi.',
            'preferred_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'consent.accepted' => 'Persetujuan penggunaan data wajib diberikan.',
        ];
    }
}