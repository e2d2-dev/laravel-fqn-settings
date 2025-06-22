<?php

namespace Betta\Settings\Enums;

enum GroupType: string
{
    case Group = 'group';
    case App = 'app';
    case Model = 'model';
    case Panel = 'panel';
}
