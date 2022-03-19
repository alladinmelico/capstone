<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserType extends Enum
{
    const ADMIN = 1;
    const FACULTY = 2;
    const STUDENT = 3;
    const GUEST = 4;
    const CLASS_PRESIDENT = 5;
    const ORG_PRESIDENT = 6;
    const STAFF = 7;
    const GUARD = 8;
}
