@extends('layouts.admin')
@section('content')
@can('families_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.families.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.community.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Community List
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
                           Community Name
                        </th>
                        <th>
                            Location
                        </th>
                        <th>
                            Current Location
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
                           Account Type
                        </th>
                        <th>
                           Status
                        </th>
                        <th>
                           Invite Link
                        </th>
                        <th>
                           Reference
                        </th>
                        <th>
                           Reference Link
                        </th>
                         <th>
                          Members
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($families as $key => $family)
                        <tr data-entry-id="{{ $family->id }}">
                            <td>

                            </td>

                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $family->name ? $family->name." Community" : '' }}
                            </td>
                            <td>
                                {{ $family->location ?? '' }}
                            </td>
                            <td>
                                {{ $family->current_location ?? '' }}
                            </td>
                            <td>
                                {!! $family->notable_individual ?? '' !!}
                            </td>
                            <td>
                               {!! Str::substr($family->about ?? '', 0 , 200) !!}
                            </td>
                            <td>
                                {{ $family->createdBy->name ?? '' }}
                            </td>
                            <td>
                                {{ $family->account_type ?? '' }}
                            </td>
                            <td>
                                {{ $family->status ?? '' }}
                            </td>
                            <td>
                            <div style="display: flex; align-items: center;">
                                <a href="{{ url('/family/invite/' . $family->invite_token) }}" id="invite-link">{{ url('/family/invite/' . $family->invite_token) }}</a>
                                <i class="fas fa-copy" onclick="copyInviteLink()" style="cursor: pointer; margin-left: 10px;"></i>
                            </div> 
                            <td>
                                @if($family->reference)
                                        <a href="{{ $family->reference }}" target="_blank">
                                            {{ trans('global.view_file') }}
                                        </a>
                                @endif
                            </td>
                            <td>
                                {{ $family->reference_link ?? '' }}
                            </td>
                             <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.families.members', $family->id) }}">View Members</a>
                            </td>
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.families.show', $family->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.families.edit', $family->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_delete')
                                    <form action="{{ route('admin.families.destroy', $family->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
        <div class="d-flex justify-content-end">
            {{ $families->links() }}
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
    order: [[ 1, 'asc' ]],
    pageLength: 100,
  });

})

</script>

<script>
function copyInviteLink() {
    var inviteLink = document.getElementById('invite-link').href;
    navigator.clipboard.writeText(inviteLink).then(function() {
        alert('Invite link copied to clipboard!');
    }, function(err) {
        alert('Failed to copy invite link');
    });
}
</script>
@endsection
