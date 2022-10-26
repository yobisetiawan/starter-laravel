<?php

namespace App\Http\Modules;

use App\Traits\HasCrudAddOn;
use App\Traits\HasCrudExtraData;
use Inertia\Inertia;

class BaseInertiaCrud extends BaseWebCrud
{

    public function __viewList($data)
    {
        return Inertia::render($this->viewPath . '/index', $data);
    }

    public function __viewCreate($data)
    {
        return Inertia::render($this->viewPath . '/Create/index', $data);
    }

    public function __viewShow($data)
    {
        return Inertia::render($this->viewPath . '/Show/index', $data);
    }

    public function __viewEdit($data)
    {
        return Inertia::render($this->viewPath . '/Edit/index', $data);
    }

    public function __extraData($data)
    {
        $data['params'] = request()->all();
        return $data;
    }

    public function __prepareListPaginationAppend($query)
    {
        foreach (request()->all() as $key => $value) {
            $query->appends($key, $value);
        }
        return  $query;
    }
}
