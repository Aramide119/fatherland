<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\User;
use App\Models\Event;
use App\Models\Member;
use App\Models\EventOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventOrderRequest;
use App\Http\Requests\UpdateEventOrderRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyEventOrderRequest;

class EventOrderController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('event_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventOrders = EventOrder::with(['event', 'user'])->get();

        return view('admin.eventOrders.index', compact('eventOrders'));
    }

    public function create()
    {
        abort_if(Gate::denies('event_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $members = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.eventOrders.create', compact('events', 'members'));
    }

    public function store(StoreEventOrderRequest $request)
    {
        $eventOrder = EventOrder::create($request->all());

        return redirect()->route('admin.event-orders.index');
    }

    public function edit(EventOrder $eventOrder)
    {
        abort_if(Gate::denies('event_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = Event::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $members = Member::pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eventOrder->load('event', 'member');

        return view('admin.eventOrders.edit', compact('eventOrder', 'events', 'members'));
    }

    public function update(UpdateEventOrderRequest $request, EventOrder $eventOrder)
    {
        $eventOrder->update($request->all());

        return redirect()->route('admin.event-orders.index');
    }

    public function show(EventOrder $eventOrder)
    {
        abort_if(Gate::denies('event_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventOrder->load('event', 'member');

        return view('admin.eventOrders.show', compact('eventOrder'));
    }

    public function destroy(EventOrder $eventOrder)
    {
        abort_if(Gate::denies('event_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyEventOrderRequest $request)
    {
        $eventOrders = EventOrder::find(request('ids'));

        foreach ($eventOrders as $eventOrder) {
            $eventOrder->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
