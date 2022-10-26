<?php

namespace App\Traits;

use App\Models\Service\Appointment;
use Illuminate\Support\Str;

trait HasCrudPrepareQuery
{
    public $disableOrderList = false;

    public $searchKeyword = 'q';

    public $customSearchable = [
        // [
        //     'morph' => 'refable',
        //     'class' => Appointment::class,
        //     'searchable' => []
        // ]
    ];

    public function __prepareQuerySearchAbleList()
    {
        $query = $this->query;

        $request = $this->requestData;

        if ($q = $request->query($this->searchKeyword)) {

            $query->where(function ($qq) use ($q) {
                $lower = "LOWER";
                $like = "like";
                foreach ($this->searchAble as $v) {
                    if (Str::contains($v, '.')) {
                        $ex = explode('.', $v);

                        $rel = implode('.', array_values(array_slice($ex, 0, count($ex) - 1)));

                        $qq->orWhereHas($rel, function ($qqq) use ($q, $ex, $lower, $like) {
                            $qqq->whereRaw(
                                $lower . '(' . $ex[count($ex) - 1] . ') '.$like.' ?',
                                ['%' . strtolower($q) . '%']
                            );
                        });
                    } else {
                        $qq->orWhereRaw($lower . '(' . $v . ') '.$like.' ?', ['%' . strtolower($q) . '%']);
                    }
                }
                $this->additionalSearchable($qq, $q);
            });
        }

        return $query;
    }

    public function additionalSearchable($query, $q)
    {
        foreach ($this->customSearchable as $data) {
            foreach ($data['searchable'] as $v) {
                $query->orWhereHasMorph($data['morph'], $data['class'], function ($qq) use ($q, $v) {
                    if (Str::contains($v, '.')) {
                        $ex = explode('.', $v);

                        $rel = implode('.', array_values(array_slice($ex, 0, count($ex) - 1)));

                        $qq->whereHas($rel, function ($qqq) use ($q, $ex) {
                            $qqq->whereRaw('LOWER(' . $ex[count($ex) - 1] . ') like ?', ['%' . strtolower($q) . '%']);
                        });
                    } else {
                        $qq->whereRaw('LOWER(' . $v . ') like ?', ['%' . strtolower($q) . '%']);
                    }
                });
            }
        }
    }

    public function __prepareQueryListType()
    {
        $query = $this->query;

        $request = $this->requestData;

        if ($request->query('type') == 'pagination') {
            $query = $query->paginate($this->paginationPerPage);
            $this->__prepareListPaginationAppend($query);

            return $query;
        }

        return $query->get();
    }

    public function __prepareListPaginationAppend($query)
    {
        $request = $this->requestData;

        foreach ($request->query() as $key => $value) {
            $query->appends($key, $value);
        }

        return  $query;
    }

    public function __prepareQueryRelationList()
    {
        $query = $this->query;

        foreach ($this->relationList as $value) {
            $query->with($value);
        }

        return $query;
    }

    public function __prepareQueryList()
    {
        return $this->query;
    }

    public function __prepareSortOrderList($query)
    {
        if ($this->disableOrderList) {
            return $query;
        }

        $request = $this->requestData;

        $sort_by = $request->query('sort_by');

        $order_by = $request->query('order_by');

        $order_by = !empty($order_by) ?  $order_by : $this->defaultOrder;

        $sort_by = !empty($sort_by) ?  $sort_by : $this->defaultSort;

        $query->orderBy($order_by, $sort_by);

        return $query;
    }

    public function __prepareQuerySortAndLimit()
    {
        $query = $this->query;

        $this->__prepareSortOrderList($query);

        $request = $this->requestData;

        $type = $request->query('type');

        $limit = $request->query('limit');

        if (!empty($limit)) {

            $this->paginationPerPage = (int) $limit;
            if ($type != 'pagination') {
                $query->limit($this->paginationPerPage);
            }
        }

        return $query;
    }

    public function __prepareDataStore($data)
    {
        return $data;
    }

    public function __prepareQueryRelationShow()
    {
        $query = $this->query;

        foreach ($this->relationShow as $value) {
            $query->with($value);
        }

        return $query;
    }

    public function __prepareLoadRelation($row)
    {
        if (!$this->lockRelationParam) {
            $relations = request('relations', '');
            if (!empty($relations)) {
                $exp = explode(',', $relations);
                $rel = [];
                foreach ($exp as $relation) {
                    if (!empty(trim($relation))) {
                        $rel[] = trim($relation);
                    }
                }
                if (!empty($rel)) {
                    $row->load($rel);
                }
            }
        }

        return $row;
    }


    public function __prepareQueryUnLockRelations()
    {
        $query = $this->query;

        if (!$this->lockRelationParam) {
            $relations = request('relations', '');
            if (!empty($relations)) {
                $exp = explode(',', $relations);
                foreach ($exp as $relation) {
                    $query->with(trim($relation));
                }
            }
        }

        return $query;
    }


    public function __prepareDataUpdate($data)
    {
        return $data;
    }

    public function __prepareQueryRowShow()
    {
        return $this->query;
    }

    public function __prepareQueryRowUpdate()
    {
        return $this->query;
    }

    public function __prepareQueryRowDestroy()
    {
        return $this->query;
    }
}
