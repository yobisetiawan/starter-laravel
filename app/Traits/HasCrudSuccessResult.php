<?php

namespace App\Traits;

trait HasCrudSuccessResult
{
    public function __successList($query)
    {
        return $this->resource::collection($query)->additional($this->__additionalCollection());
    }

    public function __successShow()
    {
        return new $this->resource($this->row);
    }

    public function __successUpdate()
    {
        return new $this->resource($this->row);
    }

    public function __successStore()
    {
        return new $this->resource($this->row);
    }

    public function __successDestroy()
    {
        return $this->__success();
    }

    public function __success()
    {
        return ['success' => true];
    }

    public function __additionalCollection()
    {
        return [];
    }
}
