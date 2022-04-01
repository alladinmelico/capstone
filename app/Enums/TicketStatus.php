<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TicketStatus extends Enum
{
    const OPEN = 1;
    const REVIEWING = 2;
    const REVIEWED = 3;
    const FIXING = 4;
    const DONE = 5;
}
