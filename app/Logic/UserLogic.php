<?php

declare(strict_types=1);

namespace App\Logic;

use App\Model\UserFacade;

class UserLogic {

    protected $user_mapper;

    function __construct(UserFacade $user_mapper) {
        $this->user_mapper = $user_mapper;
    }

    function test(): string {
        return 'hello world';
    }
}
