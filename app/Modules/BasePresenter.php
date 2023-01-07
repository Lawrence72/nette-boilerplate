<?php

declare(strict_types=1);

namespace App\Modules;

use Nette\Application\UI\Presenter;

class BasePresenter extends Presenter {
    public function beforeRender() {
        $this->template->LATTEPATH = LATTEPATH;
    }
}
