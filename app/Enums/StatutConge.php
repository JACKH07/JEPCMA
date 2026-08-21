<?php

namespace App\Enums;

enum StatutConge: string
{
    case EnAttente = 'en_attente';
    case Approuve = 'approuve';
    case Refuse = 'refuse';
}
