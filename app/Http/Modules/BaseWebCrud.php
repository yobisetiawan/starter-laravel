<?php

namespace App\Http\Modules;

use App\Traits\HasCrudAddOn;
use App\Traits\HasCrudExtraData;

class BaseWebCrud extends BaseCrud
{
    use HasCrudExtraData, HasCrudAddOn;

    public function create()
    {
        if ($ress = $this->__beforeCreate()) {
            return $ress;
        }

        $data['row'] = $this->row;

        $data = $this->__extraDataCreate($data);

        return $this->__viewCreate($data);
    }


    public function edit($id)
    {
        if ($ress = $this->__beforeEdit()) {
            return $ress;
        }

        $query = $this->model::where($this->modelKey, $id);

        $this->__prepareQueryRelationShow($query);

        $this->__prepareQueryRowShow($query);

        $this->row = $query->firstOrFail();

        $data['row'] = $this->row;

        $data = $this->__extraDataShow($data);

        return $this->__viewEdit($data);
    }
   
}
