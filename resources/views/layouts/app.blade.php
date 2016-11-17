<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    {!! Html::style('css/app.css') !!}

    {!! Html::style('css/user.css') !!}

    {!! Html::style('css/layout/master.css') !!}

    <!-- Bootstrap CSS -->
    {!! Html::style('bower/bootstrap/dist/css/bootstrap.min.css') !!}

    <!-- Bootstrap theme CSS -->
    {!! Html::style('bower/bootstrap/dist/css/bootstrap-theme.min.css') !!}

    <!-- Bootstrap datatable CSS -->
    {!! Html::style('bower/datatables.net-bs/css/dataTables.bootstrap.min.css') !!}

    <!-- Animate -->
    {!! Html::style('bower/animate.css/animate.min.css') !!}

    <!-- Social button -->
    {!! Html::style('bower/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('bower/bootstrap-social/bootstrap-social.css') !!}

    <!-- Bootstrap Tag Input css -->
    <link href="{{ asset('bower/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <!-- Bootstrap Switch css -->
    <link href="{{ asset('bower/bootstrap-switch/dist/css/bootstrap2/bootstrap-switch.min.css') }}" rel="stylesheet">

    <!-- Datetime picker -->
    <link href="{{ asset('bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <!-- Google api -->
    @if (Session::get('locale') == 'ja')
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzfBLqeROyZ1xGhOWb_oG7zmdYcCQdaI8&v=3.exp&libraries=places&language=ja">
        </script>
    @elseif(Session::get('locale') == 'vi')
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzfBLqeROyZ1xGhOWb_oG7zmdYcCQdaI8&v=3.exp&libraries=places&language=vi">
        </script>
    @else
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzfBLqeROyZ1xGhOWb_oG7zmdYcCQdaI8&v=3.exp&libraries=places&language=en">
        </script>
    @endif


</head>
<body>
    <!-- <div id="app"> -->
        <nav class="navbar navbar-inverse navbar-app">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ asset("/") }}">
                        <b class="char-app">P</b>OLL
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="menu">
                    <ul class="nav navbar-nav">
                        <li {!! Request::is('/') ? 'class="active"' : '' !!}><a href="{{ asset("/") }}">{{ trans('label.home') }}</a></li>
                        @if (auth()->check())
                            <li {!! Request::is('user/poll') ? 'class="active"' : '' !!}>
                                <a href="{{ URL::action('User\PollController@index') }}">{{ trans('polls.poll_history') }}</a>
                            </li>
                        @endif
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                            <li {!! Request::is('login') ? 'class="active"' : '' !!}>
                                <a href="{{ url('/login') }}">
                                    <span class="glyphicon glyphicon-log-in"></span> {{ trans('label.login') }}
                                </a>
                            </li>
                            <li {!! Request::is('register') ? 'class="active"' : '' !!}>
                                <a href="{{ url('/register') }}">
                                    <span class="glyphicon glyphicon-registration-mark"></span> {{ trans('label.register') }}
                                </a>
                            </li>
                        @else
                            <li {!! Request::is('user/profile') ? 'class="active"' : '' !!}>
                                <a href="{{ URL::action('User\UsersController@index') }}">
                                    <span>
                                        <img class="img-circle img-profile-header" src="{{ auth()->user()->getAvatarPath() }}">
                                        {{ str_limit(auth()->user()->name, 10) }}
                                    </span>
                                </a>
                            </li>
                            <li {!! Request::is('/logout') ? 'class="active"' : '' !!}>
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                    <span class="glyphicon glyphicon-log-out">
                                        {{ trans('label.logout') }}
                                    </span>
                                </a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endif
                        <li>
                            <div data-route="{{ url('language') }}" class="multiple-lang">
                                {{ Form::select('lang', config('settings.language'),  Session::get('locale'), ['class' => 'form-control btn-multiple-language']) }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            @yield('content')
            <script src="//code.jquery.com/jquery.js"></script>
            @include('flashy::message')
        </div>
        <div class="footer">
            <div class="row">
                <div class="col-lg-4">
                    <p><b class="char-app">P</b>oll - <b class="char-app">V</b>ote</p>
                    <p>{{ trans('label.footer.copyright') }}</p>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <p><i class="fa fa-map-marker" aria-hidden="true"></i> {{ trans('label.footer.location') }}</p>
                    </div>
                    <div class="row">
                        <p><i class="fa fa-phone" aria-hidden="true"></i> {{ trans('label.footer.email') }}</p>
                    </div>
                    <div class="row">
                        <p><i class="fa fa-envelope" aria-hidden="true"></i> {{ trans('label.footer.email') }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <p>{{ trans('label.footer.about') }}</p>
                    <p style="word-wrap: break-word">{{ trans('label.footer.description_website') }}</p>
                    <button class="btn btn-primary">
                        <span><i class="fa fa-facebook" aria-hidden="true"></i></span>
                    </button>
                    <button class="btn btn-success">
                        <span><i class="fa fa-twitter" aria-hidden="true"></i></span>
                    </button>
                    <button class="btn btn-warning">
                        <span><i class="fa fa-github" aria-hidden="true"></i></span>
                    </button>
                    <button class="btn btn-default">
                        <span><i class="fa fa-linkedin" aria-hidden="true"></i></span>
                    </button>
                </div>
            </div>
        </div>
    <!-- </div> -->

    <!-- jQuery -->
    {!! Html::script('bower/jquery/dist/jquery.min.js') !!}
    {!! Html::script('bower/jquery-validation/dist/jquery.validate.min.js') !!}

    <!-- Bootstrap -->
    {!! Html::script('bower/bootstrap/dist/js/bootstrap.min.js') !!}

    <!-- winzard -->
    {!! Html::script('bower/twitter-bootstrap-wizard/jquery.bootstrap.wizard.js') !!}

    <!-- Scripts -->
    {{--{!! Html::script('/js/app.js') !!}--}}

    {!! Html::script('js/shareSocial.js') !!}

    {!! Html::script('js/comment.js') !!}

    {!! Html::script('js/vote.js') !!}

    {!! Html::script('js/listPolls.js') !!}

    {!! Html::script('js/managePoll.js') !!}

    {!! Html::script('js/editLink.js') !!}

    {!! Html::script('js/multipleLanguage.js') !!}

    {!! Html::script('js/layout/master.js') !!}

    <!-- jQuery Datatable JavaScript -->
    {!! Html::script('/bower/datatables.net/js/jquery.dataTables.min.js') !!}

    <!-- Bootstrap Datatable JavaScript -->
    {!! Html::script('/bower/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}

    <!-- Tag Input -->
    {!! Html::script('/bower/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') !!}

    <!-- Datetime picker -->
    <script src="{{ asset('bower/moment/min/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap switch -->
    {!! Html::script('bower/bootstrap-switch/dist/js/bootstrap-switch.min.js') !!}

</body>
</html>
