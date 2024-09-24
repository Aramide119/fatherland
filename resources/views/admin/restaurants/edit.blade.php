@extends('layouts.admin')
@section('content')
@section('styles')
 <style>
        .existing-images .image-container {
            max-width: 50px;
            max-height: 50px;
        }

        .existing-images .img-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border: 1px solid #ddd;
            padding: 2px;
        }
    </style>
@endsection

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.restaurant.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.restaurants.update", [$restaurant->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="restaurant_category_id">{{ trans('cruds.content.fields.content_category') }}</label>
                <select class="form-control select2 {{ $errors->has('restaurant_category') ? 'is-invalid' : '' }}" name="restaurant_category_id" id="restaurant_category_id" required>
                    @foreach($restaurant_categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('restaurant_category_id') ? old('restaurant_category_id') : $restaurant->restaurant_category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('content_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('content_category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.content.fields.content_category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.restaurant.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $restaurant->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.restaurant.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="location">{{ trans('cruds.restaurant.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $restaurant->location) }}" required>
                @if($errors->has('location'))
                    <div class="invalid-feedback">
                        {{ $errors->first('location') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.restaurant.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ trans('cruds.restaurant.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $restaurant->phone_number) }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.restaurant.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.restaurant.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $restaurant->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.restaurant.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="website_link">{{ trans('cruds.restaurant.fields.website_link') }}</label>
                <input class="form-control {{ $errors->has('website_link') ? 'is-invalid' : '' }}" type="text" name="website_link" id="website_link" value="{{ old('website_link', $restaurant->website_link) }}">
                @if($errors->has('website_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('website_link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.restaurant.fields.website_link_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.restaurant.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $restaurant->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.restaurant.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
              <label for="photo"> {{ trans('cruds.news.fields.photo') }}</label>
              <input class="form-control" type="file" name="images[]" id="images" multiple>
              @if($errors->has('photo'))
                  <div class="invalid-feedback">
                      {{ $errors->first('photo') }}
                  </div>
              @endif
              <span class="help-block">{{ trans('cruds.news.fields.photo_helper') }}</span>

               <!-- Section to display existing images -->
                <div class="existing-images mt-3 d-flex flex-wrap">
                    @foreach($restaurant->getMedia('images') as $image)
                        <div class="image-container mr-2 mb-2">
                            <img src="{{ $image->getUrl() }}" alt="Image" class="img-thumbnail">
                        </div>
                    @endforeach
                </div>
          </div>
            <div class="form-group">
                <label>{{ trans('cruds.restaurant.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Restaurant::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $restaurant->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.restaurant.fields.status_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.restaurants.storeCKEditorImages') }}', true);
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

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

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
                data.append('crud_id', '{{ $restaurant->id ?? 0 }}');
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
});
</script>

@endsection
