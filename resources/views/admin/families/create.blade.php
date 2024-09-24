@extends('layouts.admin')
@section('styles')
     <style>
        .preview {
            margin-top: 10px;
        }

        .preview{
            max-width: 50%;
            height: 100px;
        }
    </style>
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.community.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.families.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.community.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.name_helper') }}</span>
            </div>
             <div class="form-group">
                <label class="required" for="location">{{ trans('cruds.community.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', '') }}" required>
                @if($errors->has('location'))
                    <div class="invalid-feedback">
                        {{ $errors->first('location') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="current_location">{{ trans('cruds.community.fields.current_location') }}</label>
                <input class="form-control {{ $errors->has('current_location') ? 'is-invalid' : '' }}" type="text" name="current_location" id="current_location" value="{{ old('current_location', '') }}" required>
                @if($errors->has('current_location'))
                    <div class="invalid-feedback">
                        {{ $errors->first('current_location') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.current_location_helper') }}</span>
            </div>
             <div class="form-group">
                <label class="required" for="notable_individual">{{ trans('cruds.community.fields.notable_individual') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('notable_individual') ? 'is-invalid' : '' }}" name="notable_individual" id="notable_individual">{!! old('notable_individual') !!}</textarea required>
                @if($errors->has('notable_individual'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notable_individual') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.notable_individual_helper') }}</span>
                </div>
              <div class="form-group">
                <label class="required" for="about">{{ trans('cruds.community.fields.about') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('about') ? 'is-invalid' : '' }}" name="about" id="about">{!! old('about') !!}</textarea required>
                @if($errors->has('about'))
                    <div class="invalid-feedback">
                        {{ $errors->first('about') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.about_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="profile_picture">{{ trans('cruds.community.fields.profile_picture') }}</label>
                 <input class="form-control {{ $errors->has('profile_picture') ? 'is-invalid' : '' }}" accept='' type="file" name="profile_picture" id="profile_picture" value="{{ old('profile_picture', '') }}">

                 <img id="imagePreview" class="preview" src="" alt="Image Preview" style="display:none;">

                @if($errors->has('profile_picture'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profile_picture') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.profile_picture_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cover_picture">{{ trans('cruds.community.fields.cover_picture') }}</label>
                <input class="form-control {{ $errors->has('cover_picture') ? 'is-invalid' : '' }}" accept='' type="file" name="cover_picture" id="cover_picture" value="{{ old('cover_picture', '') }}">

                 <img id="previewCover" class="preview" src="" alt="Image Preview" style="display:none;">

                @if($errors->has('cover_picture'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cover_picture') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.cover_picture_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="reference">{{ trans('cruds.community.fields.reference') }}</label>
                <input class="form-control {{ $errors->has('reference') ? 'is-invalid' : '' }}" accept='' type="file" name="reference" id="reference" value="{{ old('reference', '') }}">

                <img id="previewReference" class="preview" src="" alt="Image Preview" style="display:none;">

                @if($errors->has('reference'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reference') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.reference_helper') }}</span>
            </div>
              <div class="form-group">
                <label class="required" for="reference_link">{{ trans('cruds.community.fields.reference_link') }}</label>
                <input class="form-control {{ $errors->has('reference_link') ? 'is-invalid' : '' }}" type="url" name="reference_link" id="reference_link" value="{{ old('reference_link', '') }}">
                @if($errors->has('reference_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reference_link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.community.fields.reference_link_helper') }}</span>
            </div>
            {{--  <div class="form-group">
                <label class="required">{{ trans('cruds.group.fields.account_type') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="account_type" id="account_type" required>
                    <option value disabled {{ old('account_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Family::ACCOUNT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('account_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('account_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.group.fields.account_type_helper') }}</span>
            </div>  --}}
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
        document.getElementById('profile_picture').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('imagePreview');
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        });
</script>

<script>
        document.getElementById('cover_picture').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('previewCover');
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                const imagePreview = document.getElementById('previewCover');
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        });
</script>

<script>
        document.getElementById('reference').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('previewReference');
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                const imagePreview = document.getElementById('previewReference');
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        });
</script>

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
                xhr.open('POST', '{{ route('admin.families.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $event->id ?? 0 }}');
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
