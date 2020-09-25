<?php

namespace App\Exceptions;


class Code
{
    const SUCCESS = 0;
    const SYSTEM = 9999;
    const AUTHENTICATE = 1001;
    const AUTHORIZATION = 1002;
    const RESOURCE_NOT_FOUND = 1003;
    const MODEL_NOT_FOUND = 1004;
    const METHOD_NOT_ALLOW = 1005;
    const VALIDATION = 1006;
}
