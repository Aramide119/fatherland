@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Pending Requests
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            S/N
                        </th>
                        <th>
                           Name
                        </th>
                        <th>
                           Status
                        </th>
                        <th>
                             Joined At
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $index => $request)
                        <tr data-entry-id="{{ $request->id }}">
                            <td>

                            </td>

                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $request->user_name ?? ''}}
                            </td>
                            <td>
                                {{ $request->status ?? '' }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($request->created_at)->format('d-m-Y') }}
                            </td>
                            <td>
                                       <form action="{{ route('admin.families.accept-request', ['familyId' => $family->id, 'userId' => $request->user_id]) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-primary">Accept</button>
                                        </form>

                                        <form action="{{ route('admin.families.decline-request', ['familyId' => $family->id, 'userId' => $request->user_id]) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-danger">Decline</button>
                                        </form>

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
@can('user_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.families.massDestroy') }}",
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

@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
