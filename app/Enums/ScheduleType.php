<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ScheduleType extends Enum
{
    const WHOLE_CLASS =   'whole_class';
    const PERSONAL =   'personal';
    const OTHERS = 'others';
}
