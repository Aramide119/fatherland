@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Reported Posts
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
                           User
                        </th>
                        <th>
                            Post Title
                        </th>
                        <th>
                            Slug
                        </th>
                        <th>
                           Date Posted
                        </th>
                        <th>
                            Time Posted
                         </th>
                         <th>
                            Status
                         </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $key => $post)
                        <tr data-entry-id="{{ $post->id }}">
                            <td>

                            </td>
                            
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $post->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $post->text ?? '' }}
                            </td>
                            <td>
                                {{ $post->slug ?? '' }}
                            </td>
                            <td>
                                {!! date('Y-m-d', strtotime($post->created_at)) ? date('Y-m-d', strtotime($post->created_at)) : '' !!}
                            </td>
                            <td>
                                {!! date('H:i:s a', strtotime($post->created_at)) ? date('H:i:s a', strtotime($post->created_at)) : '' !!}
                            </td>
                            <td>
                                {{ $post->status ?? '' }}
                            </td>
                            <td>
                                @can('user_show')
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.inactivePosts.show', $post->id) }}">
                                    {{ trans('global.view') }}
                                </a>
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
//     $(function () {
//   let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
// @can('user_delete')
//   let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
//   let deleteButton = {
//     text: deleteButtonTrans,
//     url: "{{ route('admin.families.massDestroy') }}",
//     className: 'btn-danger',
//     action: function (e, dt, node, config) {
//       var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
//           return $(entry).data('entry-id')
//       });

//       if (ids.length === 0) {
//         alert('{{ trans('global.datatables.zero_selected') }}')

//         return
//       }

//       if (confirm('{{ trans('global.areYouSure') }}')) {
//         $.ajax({
//           headers: {'x-csrf-token': _token},
//           method: 'POST',
//           url: config.url,
//           data: { ids: ids, _method: 'DELETE' }})
//           .done(function () { location.reload() })
//       }
//     }
//   }
  
// @endcan

//   $.extend(true, $.fn.dataTable.defaults, {
//     orderCellsTop: true,
//     order: [[ 1, 'desc' ]],
//     pageLength: 100,
//   });
//   let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
//   $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
//       $($.fn.dataTable.tables(true)).DataTable()
//           .columns.adjust();
//   });
  
// })

</script>
@endsection