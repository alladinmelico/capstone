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
}
