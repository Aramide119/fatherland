@extends('layouts.admin')
@section('content')
@can('event_order_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            {{--  <a class="btn btn-success" href="{{ route('admin.event-orders.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.eventOrder.title_singular') }}
            </a>  --}}
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.eventOrder.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-EventOrder">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.eventOrder.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.eventOrder.fields.event') }}
                        </th>
                        <th>
                            {{ trans('cruds.eventOrder.fields.member') }}
                        </th>
                        <th>
                            {{ trans('cruds.member.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.eventOrder.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventOrders as $key => $eventOrder)
                        <tr data-entry-id="{{ $eventOrder->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $eventOrder->id ?? '' }}
                            </td>
                            <td>
                                {{ $eventOrder->event->name ?? '' }}
                            </td>
                            <td>
                                {{ $eventOrder->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $eventOrder->user->email ?? '' }}
                            </td>
                            <td>
                                {{ $eventOrder->status ?? '' }}
                            </td>
                            <td>
                                @can('event_order_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.event-orders.show', $eventOrder->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('event_order_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.event-orders.edit', $eventOrder->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('event_order_delete')
                                    <form action="{{ route('admin.event-orders.destroy', $eventOrder->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('event_order_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.event-orders.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-EventOrder:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
