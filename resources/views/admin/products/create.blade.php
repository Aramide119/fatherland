@extends('layouts.admin')
@section('content')
@section('styles')
<style>
    .previewContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top:10px;
    }
    .preview {
        display: block;
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #ddd;
        padding: 5px;
    }
</style>
@endsection

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.product') }}" enctype="multipart/form-data" id="categoryForm">
            @csrf
            <div class="form-group">
                <label for="category">Product Categories</label>
                <select class="form-control {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category" id="category" required>
                    <option value disabled {{ old('category', session('selected_category', null)) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category', session('selected_category')) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
            </div>
        </form>

        <form method="POST" action="{{ route("admin.products.store") }}" enctype="multipart/form-data" id="product">
            @csrf
          <input type="hidden" name="category_id" value="{{ session('selected_category') }}">
          <div class="form-group">
            <label for="sub_category_id">Product Sub Categories</label>
            <select class="form-control {{ $errors->has('sub_category_id') ? 'is-invalid' : '' }}" name="sub_category_id" id="sub_category_id" required>
                <option value disabled {{ old('sub_category_id', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                @if (isset($sub_categories))
                  @foreach ($sub_categories as $sub_category)
                    <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                  @endforeach
                @endif
            </select>
            @if($errors->has('event_category_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('learning_category_id') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.event.fields.category_helper') }}</span>
        </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="photo"> Product Images</label>
                <input class="form-control" type="file" name="images[]" id="images" multiple>
                <div class="previewContainer"></div>
                @if($errors->has('photo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('photo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.news.fields.photo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.product.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '') }}" step="0.01" required>
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.product.fields.description') }}</label>
               <textarea class="form-control ckeditor" name="description" id="" cols="30" rows="10"></textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="specification">{{ trans('cruds.product.fields.specification') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('specification') ? 'is-invalid' : '' }}" name="specification" id="specification" cols="30" rows="10">{{ old('specification') }}</textarea>
                @if($errors->has('specification'))
                    <div class="invalid-feedback">
                        {{ $errors->first('specification') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.specification_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="delivery_amount">{{ trans('cruds.product.fields.delivery_amount') }}</label>
                <input class="form-control {{ $errors->has('delivery_amount') ? 'is-invalid' : '' }}" type="number" name="delivery_amount" id="delivery_amount" value="{{ old('delivery_amount', '') }}" step="0.01" required>
                @if($errors->has('delivery_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.delivery_amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount">{{ trans('cruds.product.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="text" name="discount" id="discount" value="{{ old('discount', '') }}">
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subcategories">Sizes</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('sizes') ? 'is-invalid' : '' }}" name="sizes[]" id="sizes" multiple>
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('subcategories'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subcategories') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productCategory.fields.subcategory_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subcategories">Colors</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('colors') ? 'is-invalid' : '' }}" name="colors[]" id="colors" multiple>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('subcategories'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subcategories') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.productCategory.fields.subcategory_helper') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
@section('scripts')
<script>
     $(document).ready(function () {
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
              return {
                upload: function() {
                  return loader.file
                    .then(function (file) {
                      return new Promise(function(resolve, reject) {
                        // Init request
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route('admin.products.storeCKEditorImages') }}', true);
                        xhr.setRequestHeader('x-csrf-token', window._token);
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.responseType = 'json';

                        // Init listeners
                        var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                        xhr.addEventListener('error', function() { reject(genericErrorText) });
                        xhr.addEventListener('abort', function() { reject() });
                        xhr.addEventListener('load', function() {
                          var response = xhr.response;

                          if (!response || xhr.status !== 201) {
                            return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                          }

                          $('#product').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                          resolve({ default: response.url });
                        });

                        if (xhr.upload) {
                          xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                              loader.uploadTotal = e.total;
                              loader.uploaded = e.loaded;
                            }
                          });
                        }

                        // Send request
                        var data = new FormData();
                        data.append('upload', file);
                        data.append('crud_id', '{{ $product->id ?? 0 }}');
                        xhr.send(data);
                      });
                    })
                }
              };
            }
        }
    var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
})
     document.getElementById('images').addEventListener('change', function(event) {
      const files = event.target.files;
      const previewContainer = document.getElementsByClassName('previewContainer')[0];
      previewContainer.innerHTML = ''; // Clear previous previews

      for (let i = 0; i < files.length; i++) {
          const file = files[i];
          if (file) {
              const reader = new FileReader();
              reader.onload = function(e) {
                  const imagePreview = document.createElement('img');
                  imagePreview.src = e.target.result;
                  imagePreview.className = 'preview';
                  previewContainer.appendChild(imagePreview);
              };
              reader.readAsDataURL(file);
          }
      }
  });
  document.addEventListener('DOMContentLoaded', function () {
      var categorySelect = document.getElementById('category');
      categorySelect.addEventListener('change', function () {
          document.getElementById('categoryForm').submit();
      });
  });
</script>


@endsection
