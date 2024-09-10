<?php

namespace App\Enums;

enum MySQLDefault: string {
    case DEFINED = 'defined';
    case NULL = 'NULL';
    case CURRENT_TIMESTAMP = 'CURRENT_TIMESTAMP';
    case UUID = 'UUID';
}