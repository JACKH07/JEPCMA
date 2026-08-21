<?php

namespace App\Enums;

enum StatutLivraison: string
{
    case Planifiee = 'planifiee';
    case EnCours = 'en_cours';
    case Livree = 'livree';
}
