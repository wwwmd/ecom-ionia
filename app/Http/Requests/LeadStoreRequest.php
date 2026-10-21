<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('lead.create');
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:150'],
            'email'             => ['nullable', 'email', 'max:191'],
            'phone'             => ['required', 'string', 'max:25'],
            'source'            => ['required', 'in:website,phone,whatsapp,walkin,referral,social,email,other'],
            'product_interest'  => ['nullable', 'string', 'max:255'],
            'product_id'        => ['nullable', 'exists:products,id'],
            'message'           => ['nullable', 'string'],
            'status'            => ['required', 'in:new,contacted,interested,followup,negotiation,converted,lost'],
            'priority'          => ['required', 'in:low,medium,high,urgent'],
            'estimated_value'   => ['nullable', 'numeric', 'min:0'],
            'assigned_agent_id' => ['nullable', 'exists:users,id'],
            'followup_date'     => ['nullable', 'date'],
            'remarks'           => ['nullable', 'string'],
        ];
    }
}
