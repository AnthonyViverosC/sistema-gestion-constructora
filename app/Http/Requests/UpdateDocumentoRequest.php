<?php

namespace App\Http\Requests;

use App\Enums\EstadoDocumento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->rol, ['admin', 'gestor']);
    }

    public function rules(): array
    {
        return [
            'nombre_documento' => ['required', 'string', 'max:255'],
            'archivo'          => ['nullable', 'file', 'max:20480'],
            'categoria'        => ['required', 'string', 'max:100'],
            'fecha_carga'      => ['nullable', 'date'],
            'estado'           => ['required', Rule::in(EstadoDocumento::values())],
            'etiqueta'         => ['nullable', Rule::in(['Pendiente', 'Falta firma', 'Falta revisar', 'Completo'])],
            'descripcion'      => ['nullable', 'string'],
        ];
    }
}
