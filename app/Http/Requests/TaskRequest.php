<?php

namespace App\Http\Requests;

use App\Models\FileType;
use App\Utilities\Constants;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FileRequest
 *
 * @package App\Http\Requests
 */
class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(Constants::USER_ROLE['Admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'assign_to' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
        ];

        return $rules;
    }


}
