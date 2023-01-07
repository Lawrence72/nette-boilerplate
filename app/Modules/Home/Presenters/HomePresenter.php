<?php

declare(strict_types=1);

namespace App\Modules\Home\Presenters;

use App\Logic\UserLogic;
use App\Modules\BasePresenter;

final class IndexPresenter extends BasePresenter {

    protected $userLogic;

    public function __construct(UserLogic $userLogic) {
        $this->userLogic = $userLogic;
    }

    public function renderDefault() {
        $this->template->greeting = $this->userLogic->test();
    }
}
