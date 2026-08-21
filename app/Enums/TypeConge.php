<?php

namespace App\Enums;

enum TypeConge: string
{
    case Paye = 'paye';
    case SansSolde = 'sans_solde';
    case Maladie = 'maladie';
    case Maternite = 'maternite';
}
