<?php

declare(strict_types=1);


namespace App\Enum;

enum TagType: string
{
    case ARTICLE_TAG = 'article';
    case ARTICLE_KEYWORDS = 'keywords';
}
