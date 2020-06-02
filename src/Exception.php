<?php

namespace App;

use Exception as BaseException;

class Exception extends BaseException
{
    const WRONG_APPRAISAL_TYPE = 10001;
    const APPRAISAL_NOT_FOUND = 10002;

    const CAR_NOT_FOUND = 20001;
    const CAR_NOT_APPRISED = 20002;
    const COLOR_NOT_FOUND = 20003;
    const MODEL_NOT_FOUND = 20004;

    public static function throwWrongAppraisalType(int $type)
    {
        throw new self(sprintf('Wrong type "%d"', $type), self::WRONG_APPRAISAL_TYPE);
    }

    public static function throwAppraisalNotFound(string $id)
    {
        throw new self(sprintf('Appraisal "%s" was not found', $id), self::APPRAISAL_NOT_FOUND);
    }

    public static function throwCarNotFound(string $id)
    {
        throw new self(sprintf('Car "%s" was not found', $id), self::CAR_NOT_FOUND);
    }

    public static function throwCarNotAppraised(string $id)
    {
        throw new self(sprintf('Car "%s" was not appraised', $id), self::CAR_NOT_APPRISED);
    }

    public static function throwColorNotFound(int $id)
    {
        throw new self(sprintf('Color "%d" was not found', $id), self::COLOR_NOT_FOUND);
    }

    public static function throwModelNotFound(int $id)
    {
        throw new self(sprintf('Model "%d" was not found', $id), self::MODEL_NOT_FOUND);
    }
}