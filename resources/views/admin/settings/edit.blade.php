@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.resource.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.settings.update", [$setting->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="favicon">Favicon</label>
                        @if (isset($setting) && $setting->favicon)
                        <div>
                            <img src="{{ asset($setting->favicon) }}" style="max-width: 100px; max-height: 100px;">
                        </div>
                        @endif
                        <input type="file" name="favicon" id="" class="form-control {{ $errors->has('favicon') ? 'is-invalid' : '' }}" value="">
                        @if ($errors->has('favicon'))
                            <span class="invalid-feedback">{{ $errors->first('`favicon') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-6">
                        <label for="fatherland_logo">Fatherland Logo</label>
                        @if (isset($setting) && $setting->fatherland_logo)
                            <div>
                                <img src="{{ asset($setting->fatherland_logo) }}" alt="fatherland_logo" style="max-width: 100px; max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" name="fatherland_logo" id="" class="form-control {{ $errors->has('fatherland_logo') ? 'is-invalid' : '' }}">
                        @if ($errors->has('fatherland_logo'))
                            <span class="invalid-feedback">{{ $errors->first('fatherland_logo') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="linkedin_logo">LinkedIn Logo</label>
                        @if (isset($setting) && $setting->linkedin_logo)
                        <div>
                            <img src="{{ asset($setting->linkedin_logo) }}" alt="fatherland_logo" style="max-width: 100px; max-height: 100px;">
                        </div>
                    @endif
                        <input type="file" name="linkedin_logo" id="" class="form-control {{ $errors->has('linkedin_logo') ? 'is-invalid' : '' }}" >
                        @if ($errors->has('linkedin_logo'))
                            <span class="invalid-feedback">{{ $errors->first('linkedin_logo') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="linkedin_url">LinkedIn Url</label>
                        <input type="text" name="linkedin_url" id="" class="form-control {{ $errors->has('linkedin_url') ? 'is-invalid' : '' }}" value="{{ old('linkedin_url', $setting->linkedin_url) }}">
                        @if ($errors->has('linkedin_url'))
                            <span class="invalid-feedback">{{ $errors->first('linkedin_url') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="facebook_logo">Facebook Logo</label>
                        @if (isset($setting) && $setting->facebook_logo)
                        <div>
                            <img src="{{ asset($setting->facebook_logo) }}" alt="facebook_logo" style="max-width: 100px; max-height: 100px;">
                        </div>
                        @endif
                        <input type="file" name="facebook_logo" id="" class="form-control {{ $errors->has('facebook_logo') ? 'is-invalid' : '' }}">
                        @if ($errors->has('facebook_logo'))
                            <span class="invalid-feedback">{{ $errors->first('facebook_logo') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="facebook_url">Facebook Url</label>
                        <input type="text" name="facebook_url" id="" class="form-control {{ $errors->has('facebook_url') ? 'is-invalid' : '' }}" value="{{ old('facebook_url', $setting->facebook_url) }}">
                        @if ($errors->has('facebook_url'))
                            <span class="invalid-feedback">{{ $errors->first('facebook_url') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="instagram_logo">Instagram Logo</label>
                        @if (isset($setting) && $setting->instagram_logo)
                        <div>
                            <img src="{{ asset($setting->instagram_logo) }}" alt="instagram_logo" style="max-width: 100px; max-height: 100px;">
                        </div>
                        @endif
                        <input type="file" name="instagram_logo" id="" class="form-control {{ $errors->has('instagram_logo') ? 'is-invalid' : '' }}">
                        @if ($errors->has('instagram_logo'))
                            <span class="invalid-feedback">{{ $errors->first('instagram_logo') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="instagram_url">Instagram Url</label>
                        <input type="text" name="instagram_url" id="" class="form-control {{ $errors->has('instagram_url') ? 'is-invalid' : '' }}" value="{{ old('instagram_url', $setting->instagram_url) }}">
                        @if ($errors->has('instagram_url'))
                            <span class="invalid-feedback">{{ $errors->first('instagram_url') }}</span>
                        @endif
                    </div>
                </div>
                
                <button class="btn btn-danger mt-3" type="submit">{{ trans('global.save') }}</button>
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
                xhr.open('POST', '{{ route('admin.resources.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $resource->id ?? 0 }}');
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

<script>
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.resources.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="image"]').remove()
      $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($resource) && $resource->image)
      var file = {!! json_encode($resource->image) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    Dropzone.options.videoDropzone = {
    url: '{{ route('admin.resources.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="video"]').remove()
      $('form').append('<input type="hidden" name="video" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="video"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($resource) && $resource->video)
      var file = {!! json_encode($resource->video) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="video" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection