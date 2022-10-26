<?php

namespace App\Http\Modules;

use App\Http\Controllers\Controller;
use App\Traits\HasCrudHooks;
use App\Traits\HasCrudPrepareQuery;
use App\Traits\HasCrudSuccessResult;
use App\Traits\HasDBSafe;
use Illuminate\Http\Request;

class BaseCrud extends Controller
{

    use HasCrudHooks, HasCrudPrepareQuery, HasCrudSuccessResult, HasDBSafe;

    public $model;

    public $resource;

    public $row;

    public $searchAble = [];

    public $modelKey = 'id';

    public $storeValidator;

    public $updateValidator;

    public $relationList = [];

    public $relationShow = [];

    public $lockRelationParam = false;

    public $paginationPerPage = 10;

    public $defaultOrder = 'id';

    public $defaultSort = 'asc';

    public $requestData;

    public $query;


    public function index(Request $request)
    {
        $this->requestData = $request;

        $this->query = $this->model::query();

        $this->__prepareQueryRelationList();

        $this->__prepareQueryList();

        $this->__prepareQuerySearchAbleList();

        if ($ress = $this->__beforeList()) {
            return $ress;
        }

        $this->__prepareQuerySortAndLimit();

        $query = $this->__prepareQueryListType();

        $this->__prepareLoadRelation($query);

        return $this->__successList($query);
    }


    public function store(Request $request)
    {
        return $this->DBSafe(
            function () {
                $req = app($this->storeValidator);

                $this->requestData = $req;

                $dt = new $this->model();

                $data = $req->validated();

                $data = $this->__prepareDataStore($data);

                if ($ress = $this->__beforeStore()) {
                    return $ress;
                }

                $dt->fill($data);

                $dt->save();

                $this->row = $dt;

                if ($ress = $this->__afterStore()) {
                    return $ress;
                }

                $this->__prepareLoadRelation($this->row);

                return $this->__successStore();
            }
        );
    }

    public function show($id)
    {
        $this->query = $this->model::where($this->modelKey, $id);

        $this->__prepareQueryRelationShow();

        $this->__prepareQueryRowShow();

        $this->row = $this->query->firstOrFail();

        $this->__prepareLoadRelation($this->row);

        if ($ress = $this->__beforeShow()) {
            return $ress;
        }

        return $this->__successShow();
    }

    public function update(Request $request, $id)
    {
        return $this->DBSafe(
            function () use ($id) {
                $req = app($this->updateValidator);

                $this->requestData = $req;

                $this->query = $this->model::where($this->modelKey, $id);

                $this->__prepareQueryRowUpdate();

                $this->row = $this->query->firstOrFail();

                $data = $req->validated();

                $data = $this->__prepareDataUpdate($data);

                if ($ress = $this->__beforeUpdate()) {
                    return $ress;
                }

                $this->row->fill($data);

                $this->row->save();

                if ($ress = $this->__afterUpdate()) {
                    return $ress;
                }

                $this->__prepareLoadRelation($this->row);

                return $this->__successUpdate();
            }
        );
    }


    public function destroy($id)
    {
        return $this->DBSafe(
            function () use ($id) {

                $this->query = $this->model::where($this->modelKey, $id);

                $this->__prepareQueryRowDestroy();

                $this->row = $this->query->firstOrFail();

                if ($ress = $this->__beforeDestroy()) {
                    return $ress;
                }

                $this->row->delete();

                if ($ress = $this->__afterDestroy()) {
                    return $ress;
                }

                return $this->__successDestroy();
            }
        );
    }
}
