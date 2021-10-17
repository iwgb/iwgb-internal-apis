<?php

namespace Iwgb\Internal\Unwrapped\Dto;

use Iwgb\Internal\AbstractDto;

class GetUploadUrlsDto extends AbstractDto {

    public string $courierId;

    public int $count;
}