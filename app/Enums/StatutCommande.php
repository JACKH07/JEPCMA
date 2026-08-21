<?php

namespace App\Enums;

enum StatutCommande: string
{
    case Brouillon = 'brouillon';
    case Validee = 'validee';
    case Livree = 'livree';
    case Annulee = 'annulee';
}
