@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <p>{{ $family->name ? $family->name." Members" : '' }}</p>

        @if ($family->createdBy && $family->createdBy->id == Auth::id() && $family->created_by_type == get_class(Auth::user()))
            <a class="btn btn-success" href="{{ route('admin.families.request', $family->id ) }}">Pending Requests</a>
        @else

        @endif

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
                           Membership Type
                        </th>
                        <th>
                           Email
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
                    @foreach($members as $index => $member)
                        <tr data-entry-id="{{ $member->id }}">
                            <td>

                            </td>

                            <td>
                                {{ $index +1 }}
                            </td>

                            <td>
                                {{ $member->name ?? ''}}
                            </td>
                            <td>
                                 @if($member->member_type === 'admin')
                                    <button class="btn btn-xs btn-success" href="#">
                                        Admin
                                    </button>
                                 @else
                                    <button class="btn btn-xs btn-primary" href="#">
                                        Member
                                    </button>
                                 @endif
                            </td>
                            <td>
                                {{ $member->email ?? '' }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($member->created_at)->format('d-m-Y') }}
                            </td>
                            <td>
                                 @if($family->createdBy && $family->createdBy->id == Auth::id() && $family->created_by_type == get_class(Auth::user()))
                                    <form action="{{ route('admin.families.toggle-role', ['familyId' => $family->id, 'userId' => $member->id]) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-xs {{ $member->member_type == 'admin' ? 'btn-danger' : 'btn-primary' }}">
                                            {{ $member->member_type == 'admin' ? 'Remove Admin' : 'Make Admin' }}
                                        </button>
                                    </form>
                                @endif


                                    <a class="btn btn-xs btn-danger" href="{{ route('admin.families.edit', $family->id) }}">
                                        Remove
                                    </a>



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
