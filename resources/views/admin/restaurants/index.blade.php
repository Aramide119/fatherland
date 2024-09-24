@extends('layouts.admin')
@section('content')
@section('styles')
    <style>
        .img-thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
    </style>

@endsection
@can('restaurant_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.restaurants.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.restaurant.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.restaurant.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Restaurant">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.restaurant.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.restaurant.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.restaurant.fields.location') }}
                        </th>
                        <th>
                            {{ trans('cruds.restaurant.fields.phone_number') }}
                        </th>
                        <th>
                            Website Link
                        </th>
                        <th>
                            Restaurant Category
                        </th>
                        <th>
                           Images
                        </th>
                        <th>
                            {{ trans('cruds.restaurant.fields.status') }}
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
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\Restaurant::STATUS_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($restaurants as $key => $restaurant)
                        <tr data-entry-id="{{ $restaurant->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $restaurant->id ?? '' }}
                            </td>
                            <td>
                                {{ $restaurant->name ?? '' }}
                            </td>
                            <td>
                                {{ $restaurant->location ?? '' }}
                            </td>
                            <td>
                                {{ $restaurant->phone_number ?? '' }}
                            </td>
                            <td>
                                {{ $restaurant->website_link ?? '' }}
                            </td>
                            <td>
                                {{ $restaurant->restaurant_category->name ?? '' }}
                            </td>
                           <td>
                                <a href="#" data-toggle="modal" data-target="#imagesModal-{{ $restaurant->id }}">
                                    {{ trans('global.view_file') }}
                                </a>
                            </td>
                            <td>
                                {{ App\Models\Restaurant::STATUS_SELECT[$restaurant->status] ?? '' }}
                            </td>
                            <td>
                                @can('restaurant_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.restaurants.show', $restaurant->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('restaurant_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.restaurants.edit', $restaurant->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('restaurant_delete')
                                    <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                        <!-- Modal to show images -->
                 <div class="modal fade" id="imagesModal-{{ $restaurant->id }}" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel-{{ $restaurant->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imagesModalLabel-{{ $restaurant->id }}">Restaurant Images</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex flex-wrap justify-content-center">
                                    @foreach($restaurant->getMedia('images') as $image)
                                        <img src="{{ $image->getUrl() }}" alt="Image" class="img-thumbnail m-2">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
@can('restaurant_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.restaurants.massDestroy') }}",
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
  let table = $('.datatable-Restaurant:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
