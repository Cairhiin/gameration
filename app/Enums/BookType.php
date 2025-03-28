<?php

namespace App\Enums;

enum BookType: string
{
    case EBOOK      = 'ebook';
    case AUDIOBOOK  = 'audiobook';
    case PHYSICAL   = 'physical';
}
