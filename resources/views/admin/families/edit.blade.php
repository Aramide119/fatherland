@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Community
    </div>

    <div class="card-body">
          @if ($editFamily->createdBy->name != "Admin")
              <form method="POST" action="{{ route("admin.families.update", $editFamily->id ) }}" enctype="multipart/form-data">
                @csrf
                    <div class="form-group">
                      <label class="required">{{ trans('cruds.news.fields.status') }}</label>
                      <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                          <option value="" disabled {{ old('status', $editFamily->status) === null ? 'selected' : '' }}>
                              {{ trans('global.pleaseSelect') }}
                          </option>
                          <option value="pending" {{ old('status', $editFamily->status) === 'pending' ? 'selected' : ($editFamily->status === 'pending' ? 'selected' : '') }}>
                              Pending
                          </option>
                          <option value="accepted" >
                              Accepted
                          </option>
                          <option value="decline" {{ old('status', $editFamily->status) === 'decline' ? 'selected' : '' }}>
                              Decline
                          </option>
                      </select>
                      @if($errors->has('status'))
                          <div class="invalid-feedback">
                              {{ $errors->first('status') }}
                          </div>
                      @endif
                      <span class="help-block">{{ trans('cruds.news.fields.status_helper') }}</span>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                  </div>
              </form>
          @else
              <form method="POST" action="{{ route("admin.families.update", $editFamily->id ) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.community.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $editFamily->name) }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="location">{{ trans('cruds.community.fields.location') }}</label>
                    <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $editFamily->location) }}" required>
                    @if($errors->has('location'))
                        <div class="invalid-feedback">
                            {{ $errors->first('location') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.location_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="current_location">{{ trans('cruds.community.fields.current_location') }}</label>
                    <input class="form-control {{ $errors->has('current_location') ? 'is-invalid' : '' }}" type="text" name="current_location" id="current_location" value="{{ old('current_location', $editFamily->current_location) }}" required>
                    @if($errors->has('current_location'))
                        <div class="invalid-feedback">
                            {{ $errors->first('current_location') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.current_location_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="notable_individual">{{ trans('cruds.community.fields.notable_individual') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('notable_individual') ? 'is-invalid' : '' }}" name="notable_individual" id="notable_individual">{!! old('notable_individual', $editFamily->notable_individual) !!}</textarea required>
                    @if($errors->has('notable_individual'))
                        <div class="invalid-feedback">
                            {{ $errors->first('notable_individual') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.notable_individual_helper') }}</span>
                    </div>
                  <div class="form-group">
                    <label class="required" for="about">{{ trans('cruds.community.fields.about') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('about') ? 'is-invalid' : '' }}" name="about" id="about">{!! old('about', $editFamily->about) !!}</textarea required>
                    @if($errors->has('about'))
                        <div class="invalid-feedback">
                            {{ $errors->first('about') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.about_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="profile_picture">{{ trans('cruds.community.fields.profile_picture') }}</label>
                    <input class="form-control {{ $errors->has('profile_picture') ? 'is-invalid' : '' }}" accept='' type="file" name="profile_picture" id="profile_picture" value="{{ old('profile_picture', $editFamily->profile_picture) }}">

                    @if($editFamily->profile_picture)
                        <img id="imagePreview" class="preview" src="{{ $editFamily->profile_picture }}" alt="Image Preview" width="7%" height="7%" style="display:block;">
                    @else
                        <img id="imagePreview" class="preview" src="" alt="Image Preview" style="display:none;">
                    @endif

                    @if($errors->has('profile_picture'))
                        <div class="invalid-feedback">
                            {{ $errors->first('profile_picture') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.profile_picture_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="cover_picture">{{ trans('cruds.community.fields.cover_picture') }}</label>
                    <input class="form-control {{ $errors->has('cover_picture') ? 'is-invalid' : '' }}" accept='' type="file" name="cover_picture" id="cover_picture" value="{{ old('cover_picture', $editFamily->cover_picture) }}">

                    @if($editFamily->cover_picture)
                        <img id="previewCover" class="preview" src="{{ $editFamily->cover_picture }}" alt="Image Preview" width="7%" height="7%" style="display:block;">
                    @else
                        <img id="previewCover" class="preview" src="" alt="Image Preview" style="display:none;">
                    @endif

                    @if($errors->has('cover_picture'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cover_picture') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.cover_picture_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="reference">{{ trans('cruds.community.fields.reference') }}</label>
                    <input class="form-control {{ $errors->has('reference') ? 'is-invalid' : '' }}" accept='' type="file" name="reference" id="reference" value="{{ old('reference', $editFamily->reference) }}">
                    @if($editFamily->reference)
                        <img id="previewReference" class="preview" src="{{ $editFamily->reference }}" alt="Image Preview" width="7%" height="7%" style="display:block;">
                    @else
                        <img id="previewReference" class="preview" src="" alt="Image Preview" style="display:none;">
                    @endif

                    @if($errors->has('reference'))
                        <div class="invalid-feedback">
                            {{ $errors->first('reference') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.reference_helper') }}</span>
                </div>
                  <div class="form-group">
                    <label class="required" for="reference_link">{{ trans('cruds.group.fields.reference_link') }}</label>
                    <input class="form-control {{ $errors->has('reference_link') ? 'is-invalid' : '' }}" type="url" name="reference_link" id="reference_link" value="{{ old('reference_link', $editFamily->reference_link) }}">
                    @if($errors->has('reference_link'))
                        <div class="invalid-feedback">
                            {{ $errors->first('reference_link') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.community.fields.reference_link_helper') }}</span>
                </div>
                <div class="form-group">
                  <label class="required">{{ trans('cruds.news.fields.status') }}</label>
                  <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                      <option value="accepted" {{ old('status', $editFamily->status) === null ? 'selected' : '' }}>
                          {{ $editFamily->status }}
                      </option>
                      <option value="pending" {{ old('status', $editFamily->status) === 'pending' ? 'selected' : ($editFamily->status === 'pending' ? 'selected' : '') }}>
                          Pending
                      </option>
                      <option value="accepted" >
                          Accepted
                      </option>
                      <option value="decline" {{ old('status', $editFamily->status) === 'decline' ? 'selected' : '' }}>
                          Decline
                      </option>
                  </select>
                  @if($errors->has('status'))
                      <div class="invalid-feedback">
                          {{ $errors->first('status') }}
                      </div>
                  @endif
                  <span class="help-block">{{ trans('cruds.news.fields.status_helper') }}</span>
                </div>
                  <div class="form-group">
                      <button class="btn btn-danger" type="submit">
                          {{ trans('global.save') }}
                      </button>
                  </div>
              </form>
          @endif



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
                xhr.open('POST', '{{ route('admin.newss.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $news->id ?? 0 }}');
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
    Dropzone.options.photoDropzone = {
    url: '{{ route('admin.newss.storeMedia') }}',
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
      $('form').find('input[name="photo"]').remove()
      $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($news) && $news->photo)
      var file = {!! json_encode($news->photo) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
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
