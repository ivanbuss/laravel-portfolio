<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if (isset($page_title) && !empty($page_title))
        <title>{{ $page_title }}</title>
    @else
        <title>Portfolio52</title>
    @endif


    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="/bootstrap/bootstrap-datetimepicker/bootstrap-datetimepicker.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/colorbox.css">
    <link rel="stylesheet" href="/sass/main-fonts.css">
    <link rel="stylesheet" href="/sass/main.css">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/fontawesome-stars.css">
    <style type="text/css">
        #signUpModal { z-index: 9999; }
        #colorbox, #cboxOverlay { z-index: 6000; }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

</head>
<body id="app-layout">

    @yield('content')

    <!--modal with sign up-->
    @if (Auth::guest())
        <div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <a href="/login/facebook" class="btn btn-full-width btn-blue"><i class="fa fa-facebook pull-left" aria-hidden="true"></i>CONTINUE WITH FACEBOOK</a>
                        <span class="separator">or</span>
                        <a href="/register" class="btn btn-red btn-full-width"><i class="fa fa-envelope-o pull-left" aria-hidden="true"></i>SIGN UP WITH EMAIL</a>
                        <span class="term">By signing up, I agree to the Terms of Service and Privacy Policy of the site.</span></br>
                        <div class="footer">Already a Portfolio52 member? <a href="/login" class="btn pull-right">LOG IN</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <footer class="container-fluid main-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <ul class="footer-menu">
                        <li><a href="#">Our Mission</a></li>
                        <li><a href="#">Learn</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Become a Contributor</a></li>
                        <li><a href="#">Terms of Use</a></li>
                        <li><a href="#">Privacy</a></li>
                    </ul>
                    <span class="copyright">(c) Copyright 2016 Seasons Playing Cards, LTD</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 follow-us-block">
                    <h5>FOLLOW US</h5>
                    <a href="https://www.instagram.com/seasonsplayingcards"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    <a href="https://www.facebook.com/PlayingCardDB"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                    <a href="https://twitter.com/SeasonsCards"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    <a href="mailto:natsteff@gmail.com"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 newsletter-block">
                    <h5>NEWSLETTER</h5>
                    <span class="newsletter-description">Sign up for all the latest and greatest news in playing cards</span>
                    {!! Form::open(['url'=>'//seasonsplayingcards.us7.list-manage.com/subscribe/post?u=550d1fbecf972f48862bd5e83&amp;id=20d19cb06e', 'method'=>'POST']) !!}
                    <div class="input-group">
                        {!! Form::email('EMAIL', null, ['class'=>'form-control email', 'id'=>'mce-EMAIL', 'placeholder'=>'YOUR EMAIL']) !!}
                        <div style="position: absolute; left: -5000px;"><input name="b_550d1fbecf972f48862bd5e83_20d19cb06e" tabindex="-1" value="" type="text"></div>
                        <span class="input-group-btn">
                            {!! Form::submit('SIGN UP', ['class'=>'btn', 'id'=>'mc-embedded-subscribe']) !!}
                        </span>
                    </div><!-- /input-group -->
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScripts -->
    <script src="/js/jquery-1.12.4.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/js/moment.js" type="text/javascript"></script>
    <script type="text/javascript" src="/bootstrap/js/transition.js"></script>
    <script type="text/javascript" src="/bootstrap/js/collapse.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/bootstrap/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/jquery.colorbox-min.js" type="text/javascript"></script>
    <script src="/js/addNewDeck.js" type="text/javascript"></script>
    <script src="/js/scripts.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <script src="/js/animatedCounterForNumbers.js" type="text/javascript"></script>
    <script src="/js/searchFilters.js" type="text/javascript"></script>
    <script src="/js/jquery.barrating.min.js" type="text/javascript"></script>
    <script type='text/javascript' data-cfasync='false' src='//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js' data-shr-siteid='06ce0e196576bdf4239294bff485e775' async='async'></script>

    @yield('scripts')

    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
