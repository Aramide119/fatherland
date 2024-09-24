@extends('layouts.admin')
@section('content')
@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        {{--  <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
            </a>
        </div>  --}}
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.product.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.product.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.specification') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.delivery_amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.discount') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.category') }}
                        </th>
                        <th>
                            {{ trans('cruds.product.fields.sub_category') }}
                        </th>
                        <th>
                            Image
                        </th>
                        <th>
                            Sizes
                        </th>
                        <th>
                            Colors
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                        <tr data-entry-id="{{ $product->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $product->id ?? '' }}
                            </td>
                            <td>
                                {{ $product->name ?? '' }}
                            </td>
                            <td>
                                {{ $product->price ?? '' }}
                            </td>
                            <td>
                                {!! \Illuminate\Support\Str::limit($product->description ?? '', 100) !!}
                            </td>
                            <td>
                                {!! \Illuminate\Support\Str::limit($product->specification ?? '', 100) !!}
                            </td>
                            <td>
                                {{ $product->delivery_amount ?? '' }}
                            </td>
                            <td>
                                {{ $product->discount ?? '' }}
                            </td>
                            <td>
                                {{ $product->category->name ?? '' }}
                            </td>
                            <td>
                                {{ $product->sub_category->name ?? '' }}
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#imagesModal-{{ $product->id }}">
                                    {{ trans('global.view_file') }}
                                </a>
                            </td>
                            <td>
                                @foreach ($product->sizes as $size )
                                {{ $size->name}} {{ $loop->last ? '' : ', ' }}
                                @endforeach

                            </td>
                            <td>
                                @foreach ($product->colors as $color )
                                    {{ $color->name}} {{ $loop->last ? '' : ', ' }}
                                @endforeach
                            </td>
                            <td>
                                @can('product_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.products.show', $product->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('product_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.products.edit', $product->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('product_delete')
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                        <!-- Modal to show images -->
                        <div class="modal fade" id="imagesModal-{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel-{{ $product->id }}" aria-hidden="true">
                           <div class="modal-dialog modal-lg" role="document">
                               <div class="modal-content">
                                   <div class="modal-header">
                                       <h5 class="modal-title" id="imagesModalLabel-{{ $product->id }}">Product Images</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                       </button>
                                   </div>
                                   <div class="modal-body">
                                       <div class="d-flex flex-wrap justify-content-center">
                                           @foreach($product->getMedia('images') as $image)
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
@can('product_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.products.massDestroy') }}",
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
  let table = $('.datatable-Product:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
