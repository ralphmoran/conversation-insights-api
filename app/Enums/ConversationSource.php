<?php

namespace App\Enums;

enum ConversationSource: string
{
    case GOOGLE_ADS = 'google_ads';
    case WEBSITE = 'website';
    case REFERRAL = 'referral';
    case DIRECT = 'direct';
    case SOCIAL = 'social';
}
