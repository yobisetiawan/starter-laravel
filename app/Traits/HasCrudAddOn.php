<?php

namespace App\Traits;

trait HasCrudAddOn
{

    public $listWebPagination = true;
    public $viewPath = 'pages.';

    public $successStoreMsg = "Data successfully created!";
    public $successUpdateMsg = "Data successfully updated!";
    public $successDestroyMsg = "Data successfully deleted!";


    public function __viewList($data)
    {
        return view($this->viewPath . '.list', $data);
    }

    public function __viewCreate($data)
    {
        return view($this->viewPath . '.create', $data);
    }

    public function __viewShow($data)
    {
        return view($this->viewPath . '.show', $data);
    }

    public function __viewEdit($data)
    {
        return view($this->viewPath . '.edit', $data);
    }

    public function __successList($query)
    {
        $data['list'] = $query;

        $data = $this->__extraDataList($data);

        return $this->__viewList($data);
    }

    public function __successShow()
    {
        $data['row'] = $this->row;

        $data = $this->__extraDataShow($data);

        return $this->__viewShow($data);
    }

    public function __redirectSuccess()
    {
        return redirect()->back();
    }


    public function __successStore()
    {
        return $this->__redirectSuccess()->with('success_message', $this->successStoreMsg);
    }

    public function __successUpdate()
    {
        return $this->__redirectSuccess()->with('success_message', $this->successUpdateMsg);
    }

    public function __successDestroy()
    {
        return $this->__redirectSuccess()->with('success_message', $this->successDestroyMsg);
    }

    public function __prepareQueryListType()
    {
        $query = $this->query;
        if ($this->listWebPagination) {
            $query = $query->paginate($this->paginationPerPage);

            $this->__prepareListPaginationAppend($query);
            return $query;
        } else {
            return $query->get();
        }
    }
}
