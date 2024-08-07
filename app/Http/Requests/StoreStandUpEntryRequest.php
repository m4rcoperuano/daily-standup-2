<?php

namespace App\Http\Requests;

use App\Models\StandUpEntry;
use App\Models\StandUpGroup;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreStandUpEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()
            ->can('create', [
                StandUpEntry::class,
                StandUpGroup::find($this->get('stand_up_group_id'))
            ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (
                        StandUpGroup::find($this->stand_up_group_id)
                            ->standUpEntries()
                            ->where('date', Carbon::parse($value))
                            ->where('user_id', $this->user()->getKey())
                            ->exists()) {

                        $fail('Entry for this date already exists');
                    }
                }],
            'in_progress' => 'nullable|string|max:4000',
            'priorities' => 'nullable|string|max:4000',
            'blockers' => 'nullable|string|max:4000',
            'stand_up_group_id' => 'required|exists:stand_up_groups,id',
        ];
    }
}
