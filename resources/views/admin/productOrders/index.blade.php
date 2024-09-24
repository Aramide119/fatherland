@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Order {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ProductCategory">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            S/N
                        </th>
                        <th>
                            {{ trans('cruds.productCategory.fields.name') }}
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Contact
                        </th>
                        <th>
                            Address
                        </th>
                        <th>
                            Total Price
                        </th>
                        <th>
                            Delivery Amount
                        </th>
<<<<<<< HEAD
=======

                        <th>
                            Seller Status
                        </th>
>>>>>>> fe76f8e8bb72a8c5aa4a6175294eed6a75cb5324
                        <th>
                            Order Status
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                        <tr data-entry-id="{{ $order->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $order->id ?? '' }}
                            </td>
                            <td>
                                {{ $order->billingInformation->first_name." ".$order->billingInformation->last_name  ?? '' }}
                            </td>
                            <td>
                                {{ $order->billingInformation->email ?? '' }}
                            </td>
                            <td>
                                {{ $order->billingInformation->contact_number ?? '' }}
                            </td>
                            <td>
                                {{ $order->billingInformation->street_address." ".$order->billingInformation->state.", ".$order->billingInformation->country."." ?? '' }}
                            </td>
                            <td>
                                @php
                                    $orderTotalPrice = 0;
                                    foreach ($order->orderItems as $item) {
                                        $orderTotalPrice += $item->product->price * $item->quantity;
                                    }
                                @endphp
                                {{ number_format($orderTotalPrice, 2) }}
                            </td>
                            <td>
                                @php
                                    $totalDeliveryAmount = 0;
                                    foreach ($order->orderItems as $item) {
                                        $totalDeliveryAmount += $item->product->delivery_amount * $item->quantity;
                                    }
                                @endphp
                                {{ number_format($totalDeliveryAmount, 2) }}
                            </td>
                            <td>
                                {{ $order->status ?? '' }}
                            </td>
                            <td>
                                @can('product_category_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.product-orders.show', $order->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                {{-- @can('product_category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.product-categories.edit', $productCategory->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                             --}}
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
@can('product_category_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.product-categories.massDestroy') }}",
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
  let table = $('.datatable-ProductCategory:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
