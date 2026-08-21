<?php

namespace App\Enums;

enum TypeContrat: string
{
    case Cdi = 'cdi';
    case Cdd = 'cdd';
    case Stage = 'stage';
    case Alternance = 'alternance';
}
