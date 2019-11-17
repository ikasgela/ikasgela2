<?php

namespace App\Traits;

trait Etiquetas
{
    public function etiquetas()
    {
        return array_map('trim', explode(',', $this->tags));
    }

    public function hasEtiqueta($etiqueta)
    {
        return in_array($etiqueta, $this->etiquetas());
    }
}
