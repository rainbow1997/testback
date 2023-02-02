<div class="form-group col-md-12">
    <strong>{{ $field['label'] }}</strong> <br>
    <div class="dropzone sortable dz-clickable sortable">
        <div class="dz-message">
            Drop files here or click to upload.
        </div>

        @if ($entry->{$field['name']})
            @foreach($entry->{$field['name']} as $key => $image)
                <div class="dz-preview" data-id="{{ $key }}" data-path="{{ $image }}">
                    <img class="dropzone-thumbnail" src={{ asset($image) }}>
                    <a class="dz-remove" href="javascript:void(0);" data-remove="{{ $key }}" data-path="{{ $image }}">Remove file</a>
                </div>
            @endforeach
        @endif
    </div>
</div>


@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
    {{-- FIELD EXTRA CSS  --}}
    {{-- push things in the after_styles section --}}

    @push('crud_fields_styles')
        <style>
            .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; overflow: auto;}
            /*border: 1px SOLID #000;*/
            .sortable { margin: 3px 3px 3px 0; padding: 1px; float: left; /*width: 120px; height: 120px;*/ vertical-align:bottom; text-align: center; }
            .dropzone-thumbnail { width: 115px; cursor: move!important; }
            .dz-preview { cursor: move !important; }
        </style>
    @endpush

    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script>
        <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">

        <script>
            Dropzone.autoDiscover = false;
            var uploaded = false;

            var dropzone = new Dropzone(".dropzone", {
                url: "{{ url($crud->route.'/'.$entry->id.'/'.$field['upload_route']) }}",
                paramName: '{{ $field['name'] }}',
                uploadMultiple: true,
                acceptedFiles: "{{ $field['mimes'] }}",
                addRemoveLinks: true,
                // autoProcessQueue: false,
                maxFilesize: {{ $field['filesize'] }},
                parallelUploads: 10,
                // previewTemplate:
                sending: function(file, xhr, formData) {
                    formData.append("_token", $('[name=_token').val());
                    formData.append("id", {{ $entry->id }});
                },
                error: function(file, response) {
                    console.log('error');
                    console.log(file)
                    console.log(response)

                    $(file.previewElement).find('.dz-error-message').remove();
                    $(file.previewElement).remove();

                    $(function(){
                        new PNotify({
                            title: file.name+" was not uploaded!",
                            text: response,
                            type: "error",
                            icon: false
                        });
                    });

                },
                success : function(file, status) {
                    console.log('success');

                    // clear the images in the dropzone
                    $('.dropzone').empty();

                    // repopulate the dropzone with all images (new and old)
                    $.each(status.images, function(key, image_path) {
                        $('.dropzone').append('<div class="dz-preview" data-id="'+key+'" data-path="'+image_path+'"><img class="dropzone-thumbnail" src="{{ url('') }}/'+image_path+'" /><a class="dz-remove" href="javascript:void(0);" data-remove="'+key+'" data-path="'+image_path+'">Remove file</a></div>');
                    });

                    var notification_type;

                    if (status.success) {
                        notification_type = 'success';
                    } else {
                        notification_type = 'error';
                    }

                    new PNotify({
                        text: status.message,
                        type: notification_type,
                        icon: false
                    });
                }
            });

            // Reorder images
            $(".dropzone").sortable({
                items: '.dz-preview',
                cursor: 'move',
                opacity: 0.5,
                containment: '.dropzone',
                distance: 20,
                scroll: true,
                tolerance: 'pointer',
                stop: function (event, ui) {
                    // console.log('sortable stop');
                    var image_order = [];

                    $('.dz-preview').each(function() {
                        var image_id = $(this).data('id');
                        var image_path = $(this).data('path');
                        image_order.push({ id: image_id, path: image_path});
                    });

                    // console.log(image_order);

                    $.ajax({
                        url: '{{ url($crud->route.'/'.$entry->id.'/'.$field['reorder_route']) }}',
                        type: 'POST',
                        data: {
                            order: image_order,
                            entry_id: {{ $entry->id }}
                        },
                    })
                        .done(function(status) {
                            var notification_type;

                            if (status.success) {
                                notification_type = 'success';
                            } else {
                                notification_type = 'error';
                            }

                            new PNotify({
                                text: status.message,
                                type: notification_type,
                                icon: false
                            });
                        });
                }
            });

            // Delete image
            $(document).on('click', '.dz-remove', function () {
                var image_id = $(this).data('remove');
                var image_path = $(this).data('path');

                $.ajax({
                    url: '{{ url($crud->route.'/'.$entry->id.'/'.$field['delete_route']) }}',
                    type: 'POST',
                    data: {
                        entry_id: {{ $entry->id }},
                        image_id: image_id,
                        image_path: image_path
                    },
                })
                    .done(function(status) {
                        var notification_type;

                        if (status.success) {
                            notification_type = 'success';
                            $('div.dz-preview[data-id="'+image_id+'"]').remove();
                        } else {
                            notification_type = 'error';
                        }

                        new PNotify({
                            text: status.message,
                            type: notification_type,
                            icon: false
                        });
                    });

            });

        </script>

    @endpush
@endif
