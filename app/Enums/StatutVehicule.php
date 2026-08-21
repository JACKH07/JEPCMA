<?php

namespace App\Enums;

enum StatutVehicule: string
{
    case Disponible = 'disponible';
    case EnMission = 'en_mission';
    case Maintenance = 'maintenance';
}
