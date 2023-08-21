@extends('admin.layouts.app')
@section('panel')
    <section style="background-color: #eee; border-radius: 10px;">
        <div class="container py-3">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0 font-weight-bold">Project Status</p>
                                </div>

                                <div class="col-sm-9">
                                    @if ($project->status == 1)
                                        <span class="badge badge--success font-weight-bold">@lang('Project is Actived')</span>
                                    @elseif($project->status == 2)
                                        <span class="badge badge--danger font-weight-bold">@lang('Project is Banned')</span>
                                    @else
                                        <span class="badge badge--primary font-weight-bold">@lang('Pending for Admin Approval.')</span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0 font-weight-bold">Project Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ __($project->project_name) }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0 font-weight-bold">Project URL</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ __($project->url) }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0 font-weight-bold">Username</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ __($project->username) }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0 font-weight-bold">Password</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ __($project->password) }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0 font-weight-bold">Files</p>
                                </div>
                                <div class="col-sm-9">
                                    @php
                                        $files_view = '';
                                        $filesname = json_decode($project->files);

                                        foreach ($filesname as $filename) {
                                            $files_view .= '<a href="https://manage.forayeji.com/assets/files/project/' . $filename . '">' . $filename . '</a> <a href="/admin/project/delete/' . $filename . '"><i class="text-danger las la-trash-alt"></i></a></br>';
                                        }
                                        echo $files_view;
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body pt-0">
                            <!-- Chat ------------------------------------------------------------>
                            <div class="page-content page-container" id="page-content">
                                <div class="row d-flex justify-content-center">
                                    <div class="card-header">
                                        <h4 class="card-title"><strong>Messages</strong></h4>
                                    </div>
                                    <div class="ps-container ps-theme-default ps-active-y" id="chat-content"
                                        style="overflow-y: scroll !important; height:400px !important;">
                                        <div class="media media-chat">
                                            <img class="avatar"
                                                src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                alt="...">
                                            <div class="media-body">
                                                <p>Hi</p>
                                                <p class="meta"><time datetime="2018">23:58</time></p>
                                            </div>
                                        </div>

                                        {{-- <div class="media media-meta-day">Today</div> --}}

                                        <div class="media media-chat media-chat-reverse">
                                            <div class="media-body">
                                                <p>Hiii, I'm good.</p>
                                                <p class="meta"><time datetime="2018">00:06</time></p>
                                            </div>
                                        </div>

                                        <div class="media media-chat">
                                            <img class="avatar"
                                                src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                alt="...">
                                            <div class="media-body">
                                                <p>Okay</p>
                                                <p>We will go on sunday? </p>
                                                <p class="meta"><time datetime="2018">00:07</time></p>
                                            </div>
                                        </div>

                                        <div class="media media-chat media-chat-reverse">
                                            <div class="media-body">
                                                <p>That's awesome!</p>
                                                <p>I will meet you Sandon Square sharp at 10 AM</p>
                                                <p>Is that okay?</p>
                                                <p class="meta"><time datetime="2018">00:09</time></p>
                                            </div>
                                        </div>

                                        <div class="media media-chat">
                                            <img class="avatar"
                                                src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                alt="...">
                                            <div class="media-body">
                                                <p>Okay i will meet you on Sandon Square </p>
                                                <p class="meta"><time datetime="2018">00:10</time></p>
                                            </div>
                                        </div>

                                        <div class="media media-chat media-chat-reverse">
                                            <div class="media-body">
                                                <p>Do you have pictures of Matley Marriage?</p>
                                                <p class="meta"><time datetime="2018">00:10</time></p>
                                            </div>
                                        </div>

                                        <div class="media media-chat">
                                            <img class="avatar"
                                                src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                alt="...">
                                            <div class="media-body">
                                                <p>Sorry I don't have. i changed my phone.</p>
                                                <p class="meta"><time datetime="2018">00:12</time></p>
                                            </div>
                                        </div>

                                        <div class="media media-chat media-chat-reverse">
                                            <div class="media-body">
                                                <p>Okay then see you on sunday!!</p>
                                                <p class="meta"><time datetime="2018">00:12</time></p>
                                            </div>
                                        </div>

                                        <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                                            <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                        </div>
                                        <div class="ps-scrollbar-y-rail" style="top: 0px; height: 0px; right: 2px;">
                                            <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 2px;">
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.project.message') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="publisher bt-1 border-light">
                                            <img class="avatar avatar-xs"
                                                src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                alt="...">
                                                <input type="number" name="project_id" hidden value="{{ __($project->id) }}">
                                                <input type="number" name="user_id" hidden value="{{auth()->guard('admin')->user()->id}}">
                                            <input class="publisher-input" type="text" name="message" placeholder="Write something">

                                            <a class="publisher-btn" href="#" data-abc="true"><i
                                                    class="fa fa-smile"></i></a>
                                            <a class="publisher-btn text-info" onclick="this.closest('form').submit();return false;" data-abc="true"><i
                                                    class="fa fa-paper-plane"></i></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('breadcrumb-plugins')
    <a href="{{ route('admin.project.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush
