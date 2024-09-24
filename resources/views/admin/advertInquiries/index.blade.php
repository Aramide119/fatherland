@extends('layouts.admin')
@section('content')
@can('advert_inquiry_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.advert-inquiries.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.advertInquiry.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.advertInquiry.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-AdvertInquiry">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.first_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.last_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.location') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.company_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.comments') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advertInquiries as $key => $advertInquiry)
                        <tr data-entry-id="{{ $advertInquiry->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $advertInquiry->id ?? '' }}
                            </td>
                            <td>
                                {{ $advertInquiry->first_name ?? '' }}
                            </td>
                            <td>
                                {{ $advertInquiry->last_name ?? '' }}
                            </td>
                            <td>
                                {{ $advertInquiry->location ?? '' }}
                            </td>
                            <td>
                                {{ $advertInquiry->email ?? '' }}
                            </td>
                            <td>
                                {{ $advertInquiry->company_name ?? '' }}
                            </td>
                            <td>
                                {{ $advertInquiry->comments ?? '' }}
                            </td>
                            <td>
                                @can('advert_inquiry_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.advert-inquiries.show', $advertInquiry->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('advert_inquiry_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.advert-inquiries.edit', $advertInquiry->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('advert_inquiry_delete')
                                    <form action="{{ route('admin.advert-inquiries.destroy', $advertInquiry->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('advert_inquiry_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.advert-inquiries.massDestroy') }}",
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
  let table = $('.datatable-AdvertInquiry:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection