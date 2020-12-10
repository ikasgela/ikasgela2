@include('partials.subtitulo', ['subtitulo' => __('Continuous evaluation')])

<div class="card-deck">
    <div
            class="card mb-3 {{ $num_actividades_obligatorias > 0 ? $actividades_obligatorias ? 'bg-success text-white' : 'bg-warning text-dark' : 'bg-light text-dark' }}">
        <div
                class="card-header">{{ __('Mandatory activities') }}</div>
        <div class="card-body text-center">
            <p class="card-text"
               style="font-size:150%;">{{ $num_actividades_obligatorias > 0 ? $actividades_obligatorias ? trans_choice('tasks.completed', 2) : ($numero_actividades_completadas+0)."/".($num_actividades_obligatorias+0)  : __('None') }}</p>
        </div>
    </div>
    <div
            class="card mb-3 {{ $num_pruebas_evaluacion > 0 ? $pruebas_evaluacion ? 'bg-success text-white' : 'bg-warning text-dark' : 'bg-light text-dark' }}">
        <div class="card-header">{{ __('Assessment tests') }}</div>
        <div class="card-body text-center">
            <p class="card-text"
               style="font-size:150%;">
                {{ $num_pruebas_evaluacion > 0 ? $pruebas_evaluacion ? trans_choice('tasks.passed', 2) : trans_choice('tasks.not_passed', 2) : __('None') }}</p>
        </div>
    </div>
    <div
            class="card mb-3 {{ ($actividades_obligatorias || $num_actividades_obligatorias == 0)
                && ($pruebas_evaluacion || $num_pruebas_evaluacion == 0)
                && $competencias_50_porciento && $nota_final >= 5 ? 'bg-success text-white' : 'bg-warning text-dark' }}">
        <div class="card-header">{{ __('Continuous evaluation') }}</div>
        <div class="card-body text-center">
            <p class="card-text"
               style="font-size:150%;">{{ ($actividades_obligatorias || $num_actividades_obligatorias == 0)
                && ($pruebas_evaluacion || $num_pruebas_evaluacion == 0)
                && $competencias_50_porciento && $nota_final >= 5 ? trans_choice('tasks.passed', 1) : trans_choice('tasks.not_passed', 1) }}</p>
        </div>
    </div>
    <div
            class="card mb-3 {{ ($actividades_obligatorias || $num_actividades_obligatorias == 0)
                && ($pruebas_evaluacion || $num_pruebas_evaluacion == 0)
                && $competencias_50_porciento ? $nota_final >= 5 ? 'bg-success text-white' : 'bg-warning text-dark' : 'bg-light text-dark' }}">
        <div class="card-header">{{ __('Calification') }}</div>
        <div class="card-body text-center">
            <p class="card-text"
               style="font-size:150%;">{{ $competencias_50_porciento ? $nota_final : __('Unavailable') }}</p>
        </div>
    </div>
</div>
