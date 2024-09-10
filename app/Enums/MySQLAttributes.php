<?php

namespace App\Enums;

enum MySQLAttributes: string {
    case BINARY = 'BINARY';
    case UNSIGNED = 'UNSIGNED';
    case UNSIGNED_ZEROFILL = 'UNSIGNED ZEROFILL';
    case ON_UPDATE_CURRENT_TIMESTAMP = 'on update CURRENT_TIMESTAMP';
    case COMPRESSED = 'COMPRESSED=zlib';
}