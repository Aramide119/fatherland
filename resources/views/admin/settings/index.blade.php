@extends('layouts.admin')
@section('content')
@can('resource_category_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.settings.create') }}">
                {{ trans('global.add') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Settings
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Resource">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Favicon
                        </th>
                        <th>
                            Fatherland Logo
                        </th>
                        <th>
                            Instagram Logo
                        </th>
                        <th>
                            Instagram URL
                        </th>
                        <th>
                            LinkedIn Logo
                        </th>
                        <th>
                            LinkedIn URL
                        </th>
                        <th>
                            Facebook Logo
                        </th>
                        <th>
                            Facebook URLj
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                        <tr data-entry-id="{{ $setting->id ?? '' }}">
                            <td>

                            </td>
                            <td>
                                {{ $setting->id ?? '' }}
                            </td>
                            <td>
                                @if (isset($setting) && $setting->favicon)
                                <div>
                                    <img src="{{ asset($setting->favicon) }}" style="max-width: 70px; max-height: 70px;">
                                </div>
                                @endif
                            </td>
                            <td>
                                @if (isset($setting) && $setting->fatherland_logo)
                                <div>
                                    <img src="{{ asset($setting->fatherland_logo) }}" alt="fatherland_logo" style="max-width: 70px; max-height: 70px;">
                                </div>
                                @endif
                            </td>
                            <td>
                                @if (isset($setting) && $setting->instagram_logo)
                                    <div>
                                        <img src="{{ asset($setting->instagram_logo) }}" alt="instagram_logo" style="max-width: 70px; max-height: 70px;">
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $setting->instagram_url ?? '' }}
                            </td>
                            <td>
                                @if (isset($setting) && $setting->linkedin_logo)
                                    <div>
                                        <img src="{{ asset($setting->linkedin_logo) }}" alt="fatherland_logo" style="max-width: 70px; max-height: 70px;">
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $setting->linkedin_url ?? '' }}
                            </td>
                            <td>
                                @if (isset($setting) && $setting->facebook_logo)
                                <div>
                                    <img src="{{ asset($setting->facebook_logo) }}" alt="facebook_logo" style="max-width: 70px; max-height: 70px;">
                                </div>
                                @endif
                            </td>
                            <td>
                                {{ $setting->facebook_url ?? '' }}
                            </td>
                            <td>
                                @can('resource_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.settings.show', $setting->id ?? '') }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('resource_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.settings.edit', $setting->id ?? '') }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                            </td>

                        </tr>
                        {{-- @endforeach --}}
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
@can('resource_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.resources.massDestroy') }}",
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