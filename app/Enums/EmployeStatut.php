<?php

namespace App\Enums;

enum EmployeStatut: string
{
    case Actif = 'actif';
    case Inactif = 'inactif';
    case Suspendu = 'suspendu';
}
