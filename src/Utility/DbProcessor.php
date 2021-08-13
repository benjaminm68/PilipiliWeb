<?php

namespace App\Utility;

class DbProcessor
{
    public function __invoke(array $record)
    {
        // On modifie le record pour y ajouter nos infos
        return $record;
    }
}