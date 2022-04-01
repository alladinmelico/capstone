<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TicketType extends Enum
{
    const SCANNER = 'RFID scanner issue';
    const TEMPERATURE = 'Temperature Scanner issue';
    const MONITOR = 'No Data displaying on the monitor';
    const WRONG_DATA = 'Wrong data on display';
    const COMPUTER = 'Computer shuts down';
}
