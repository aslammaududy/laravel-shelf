<?php

namespace App\Enums;

enum DocumentType: string
{
    case CONTRACT = 'contract';
    case INVOICE = 'invoice';
    case RECEIPT = 'receipt';
    case HR_RECORD = 'hr_record';
    case GENERAL = 'general';
}
