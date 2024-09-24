<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTravelOrderRequest;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderRequest;
use App\Models\Member;
use App\Models\Travel;
use App\Models\TravelOrder;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TravelOrderController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('travel_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $travelOrders = TravelOrder::with(['travel', 'member'])->get();

        $travels = Travel::get();

        $members = Member::get();

        return view('admin.travelOrders.index', compact('members', 'travelOrders', 'travels'));
    }

    public function create()
    {
        abort_if(Gate::denies('travel_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $travel = Travel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $members = Member::pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.travelOrders.create', compact('members', 'travel'));
    }

    public function store(StoreTravelOrderRequest $request)
    {
        $travelOrder = TravelOrder::create($request->all());

        return redirect()->route('admin.travel-orders.index');
    }

    public function edit(TravelOrder $travelOrder)
    {
        abort_if(Gate::denies('travel_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $travel = Travel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $members = Member::pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $travelOrder->load('travel', 'member');

        return view('admin.travelOrders.edit', compact('members', 'travel', 'travelOrder'));
    }

    public function update(UpdateTravelOrderRequest $request, TravelOrder $travelOrder)
    {
        $travelOrder->update($request->all());

        return redirect()->route('admin.travel-orders.index');
    }

    public function show(TravelOrder $travelOrder)
    {
        abort_if(Gate::denies('travel_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $travelOrder->load('travel', 'member');

        return view('admin.travelOrders.show', compact('travelOrder'));
    }

    public function destroy(TravelOrder $travelOrder)
    {
        abort_if(Gate::denies('travel_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $travelOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyTravelOrderRequest $request)
    {
        $travelOrders = TravelOrder::find(request('ids'));

        foreach ($travelOrders as $travelOrder) {
            $travelOrder->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
