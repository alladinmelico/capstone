<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const ADMIN = 1;
    const FACULTY = 2;
    const STUDENT = 3;
    const GUEST = 4;
    const CLASS_PRESIDENT = 5;
    const STAFF = 6;
    const GUARD = 7;
}
