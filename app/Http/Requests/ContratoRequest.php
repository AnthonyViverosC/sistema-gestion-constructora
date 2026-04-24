<?php

namespace App\Http\Requests;

use App\Enums\EstadoContrato;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->rol, ['admin', 'gestor']);
    }

    public function rules(): array
    {
        $contratoId = $this->route('contrato')?->id;

        return [
            'numero_contrato'    => ['required', 'string', 'max:50', Rule::unique('contratos', 'numero_contrato')->ignore($contratoId)],
            'fecha_contrato'     => ['required', 'date'],
            'fecha_inicio'       => ['nullable', 'date'],
            'fecha_fin'          => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'cedula_contratista' => ['required', 'string', 'max:20'],
            'nombre_contratista' => ['required', 'string', 'max:150'],
            'estado'             => ['required', Rule::in(EstadoContrato::values())],
            'etiqueta'           => ['nullable', Rule::in(['Pendiente', 'Falta firma', 'Falta revisar', 'Completo'])],
            'descripcion'        => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_contrato.required'    => 'El número de contrato es obligatorio.',
            'numero_contrato.unique'      => 'Este número de contrato ya existe.',
            'fecha_contrato.required'     => 'La fecha del contrato es obligatoria.',
            'fecha_inicio.date'           => 'La fecha de inicio no es válida.',
            'fecha_fin.date'              => 'La fecha fin no es válida.',
            'fecha_fin.after_or_equal'    => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
            'cedula_contratista.required' => 'La cédula del contratista es obligatoria.',
            'nombre_contratista.required' => 'El nombre del contratista es obligatorio.',
            'estado.required'             => 'El estado es obligatorio.',
        ];
    }
}
