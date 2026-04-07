<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'type'             => $this->type,
            'source'           => $this->source,
            'amount'           => (float) $this->amount,
            'description'      => $this->description,
            'reference_no'     => $this->reference_no,
            'transaction_date' => $this->transaction_date?->format('Y-m-d'),
            'is_categorized'   => $this->is_categorized,
            'category'         => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),
            'business'         => $this->whenLoaded('business', fn() => [
                'id'   => $this->business->id,
                'name' => $this->business->name,
            ]),
            'bank_account'     => $this->whenLoaded('bankAccount', fn() => [
                'id'   => $this->bankAccount->id,
                'name' => $this->bankAccount->bank_name ?? '',
            ]),
            'receipt_url'      => $this->receipt_path ? \Illuminate\Support\Facades\Storage::url($this->receipt_path) : null,
            'receipt_type'     => $this->receipt_type,
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
