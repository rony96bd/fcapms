@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                	<form action="{{route('admin.project.store')}}" method="POST" enctype="multipart/form-data">
                		@csrf
                		<div class="row">
                           <div class="col-lg-8">
		                		<div class="form-group">
		                			<label for="project_name" class="font-weight-bold">@lang('Project Name')</label>
		                			<input type="text" name="project_name" id="project_name" value="{{old('project_name')}}" class="form-control form-control-lg" placeholder="@lang('Enter Project Name')" maxlength="80" required="">
		                		</div>
		                	</div>
		                </div>

                        <div class="row">
                            <div class="col-lg-8">
                                 <div class="form-group">
                                     <label for="url" class="font-weight-bold">@lang('Project URL')</label>
                                     <input type="text" name="url" id="url" value="{{old('url')}}" class="form-control form-control-lg" placeholder="@lang('Enter Project URL')" required="">
                                 </div>
                             </div>
                         </div>

                         <div class="row">
                            <div class="col-lg-8">
                                 <div class="form-group">
                                     <label for="username" class="font-weight-bold">@lang('Username')</label>
                                     <input type="text" name="username" id="username" value="{{old('username')}}" class="form-control form-control-lg" placeholder="@lang('Enter Username')" required="">
                                 </div>
                             </div>
                         </div>

                         <div class="row">
                            <div class="col-lg-8">
                                 <div class="form-group">
                                     <label for="password" class="font-weight-bold">@lang('Password')</label>
                                     <input type="text" name="password" id="password" value="{{old('password')}}" class="form-control form-control-lg" placeholder="@lang('Enter Password')" required="">
                                 </div>
                             </div>
                         </div>

                         <div class="form-group ">
                            <label class="form-control-label font-weight-bold" for="file">@lang('Upload Files')
                                <sup class="text--danger">*</sup></label>
                                    <div class="col">
                                        <input type="file" id="files" name="files[]" multiple
                                            class="form--control custom-file-upload"><br>
                            </div>
                        </div>

                         <div class="col-lg-8">
                       	<div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg"><i class="fa fa-fw fa-paper-plane"></i> @lang('Create Project')</button>
                        </div>
                    </div>
                	</form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <a href="{{route('admin.project.index')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush

@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        $('.datepicker-here').datepicker({
            autoClose: true,
            dateFormat: 'yyyy-mm-dd',
        });

        $('select[name=city]').on('change',function() {
            $('select[name=location]').html('<option value="" selected="" disabled="">@lang('Select One')</option>');
            var locations = $('select[name=city] :selected').data('locations');
            var html = '';
            locations.forEach(function myFunction(item, index) {
                html += `<option value="${item.id}">${item.name}</option>`
            });
            $('select[name=location]').append(html);
        });
    </script>
@endpush


