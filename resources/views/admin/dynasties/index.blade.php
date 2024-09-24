@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Conversation List
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
                           Conversation Name
                        </th>
                        <th>
                            Location
                        </th>
                        <th>
                           Notable Individual
                        </th>
                        <th>
                           About
                        </th>
                        <th>
                           Created By
                        </th>
                        <th>
                           Status
                        </th>
                        <th>
                           Reference
                        </th>
                        <th>
                           Reference Link
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dynasties as $key => $dynasty)
                        <tr data-entry-id="{{ $dynasty->id }}">
                            <td>

                            </td>

                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $dynasty->name ?? '' }}
                            </td>
                            <td>
                                {{ $dynasty->location ?? '' }}
                            </td>
                            <td>
                                {{ $dynasty->notable_individual ?? '' }}
                            </td>
                            <td>
                               {!! Str::substr($dynasty->about ?? '', 0 , 200) !!}
                            </td>
                            <td>
                                {{ $dynasty->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $dynasty->status ?? '' }}
                            </td>
                            <td>
                                @if($dynasty->reference)
                                        <a href="{{ $dynasty->reference }}" target="_blank">
                                            {{ trans('global.view_file') }}
                                        </a>
                                @endif
                            </td>
                            <td>
                                @if($dynasty->reference_link)
                                <a href="{{ $dynasty->reference_link ?? '' }}" target="_blank">
                                            View Link
                                </a>
                                @else
                                    No Link
                                @endif

                            </td>
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.dynasties.show', $dynasty->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.dynasties.edit', $dynasty->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_delete')
                                    <form action="{{ route('admin.dynasties.destroy', $dynasty->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
