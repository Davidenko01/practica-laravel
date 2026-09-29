<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\IdeaState;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdeaRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:3', 'max:255'],
            'state' => ['required', Rule::enum(IdeaState::class)],
            'links' => ['nullable', 'array', 'max:10'],
            'links.*' => ['required', 'url', 'max:255', 'distinct'],
            'steps' => ['nullable', 'array', 'max:20'],
            'steps.*' => ['array:id,description'],
            'steps.*.id' => ['nullable', 'integer', Rule::exists('steps', 'id')->where('idea_id', $this->route('idea')?->id)],
            'steps.*.description' => ['required', 'string', 'max:255', 'distinct'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El campo titulo es obligatorio.',
            'title.min' => 'El campo titulo debe tener minimo 3 caracteres.',
            'title.max' => 'El campo titulo debe tener maximo 255 caracteres.',
            'description.required' => 'El campo descripcion es obligatorio.',
            'description.min' => 'El campo descripcion debe tener minimo 3 caracteres.',
            'description.max' => 'El campo descripcion debe tener maximo 255 caracteres.',
            'state.required' => 'El campo estado es obligatorio.',
            'state.enum' => 'El estado seleccionado no es valido.',
            'links.array' => 'Los links deben ser una lista.',
            'links.max' => 'No podes agregar mas de 10 links.',
            'links.*.required' => 'El link no puede estar vacio.',
            'links.*.url' => 'Cada link debe ser una URL valida.',
            'links.*.max' => 'Cada link debe tener maximo 255 caracteres.',
            'links.*.distinct' => 'No podes repetir el mismo link.',
            'steps.array' => 'Los pasos deben ser una lista.',
            'steps.max' => 'No podes agregar mas de 20 pasos.',
            'steps.*.array' => 'Cada paso debe tener un formato valido.',
            'steps.*.id.exists' => 'El paso no pertenece a esta idea.',
            'steps.*.description.required' => 'El paso no puede estar vacio.',
            'steps.*.description.max' => 'Cada paso debe tener maximo 255 caracteres.',
            'steps.*.description.distinct' => 'No podes repetir el mismo paso.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen debe pesar maximo 5 MB.',
        ];
    }
}
