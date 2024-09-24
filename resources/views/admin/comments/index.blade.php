@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Comment {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-News">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            S/N
                        </th>
                        <th>
                            User
                        </th>
                        <th>
                            Comments
                        </th>
                        <th>
                            News
                        </th>
                        <th>
                            Replies
                        </th>
                        <th>
                            Date
                        </th>
                        <th>
                            Time
                        </th>
                        <th>
                            {{ trans('cruds.news.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                           
                        </td>
                        <td>
                           
                        </td>
                        <td>
                           
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allComments as $key => $allComment)
                        <tr data-entry-id="{{ $allComment->id }}">
                            <td>

                            </td>
                            <td>
                                  {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $allComment->user->name ?? '' }}
                            </td>
                            <td>
                                 {{ $allComment->comment ?? '' }}
                            </td>
                            <td>
                                  {{ $allComment->news->title ?? '' }}
                            </td>
                            <td>
                                {{-- Count the number of replies for this comment --}}
                                @php
                                    $replyCount = $allComment->replies->count();
                                @endphp
                                {{ $replyCount }}
                            </td>
                            <td>
                            {!! date('Y-m-d', strtotime($allComment->created_at)) ? date('Y-m-d', strtotime($allComment->created_at)) : '' !!}
                            </td>
                            <td>
                                  {!! date('h:i:s a', strtotime($allComment->created_at)) ? date('h:i:s a', strtotime($allComment->created_at))  : '' !!}
                            </td>
                            <td>
                                 {{ $allComment->status }}
                            </td>
                            <td>
                                @can('news_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.comments.show', $allComment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('news_delete')
                                    <a class="btn btn-xs btn-success" href="{{ route('admin.comments.reply', $allComment->id) }}">
                                        Reply
                                    </a>
                                @endcan

                                @can('news_edit')
                                    <!-- <a class="btn btn-xs btn-info" href="{{ route('admin.newss.edit', $allComment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a> -->
                                    @if ($allComment->status == 'active')
                                            <form action="{{ route('admin.comments.changeStatus', $allComment->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-xs btn-danger">Deactivate</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.comments.changeStatus', $allComment->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-xs btn-warning">Activate</button>
                                            </form>
                                        @endif
                                @endcan

                            </td>
<!--  -->
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
@can('news_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.newss.massDestroy') }}",
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
  let table = $('.datatable-News:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection