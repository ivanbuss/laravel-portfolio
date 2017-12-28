<div class="brand clearfix">
    <a href="{{ url('admin') }}" class="logo"><img src="{{ url('/panel/img/logo.jpg') }}" class="img-responsive" alt=""></a>
    <span class="menu-btn"><i class="fa fa-bars"></i></span>
    <ul class="ts-profile-nav">
        <li><a href="#">Help</a></li>
        <li><a href="#">Settings</a></li>
        <li class="ts-account">
            <a href="{{ url('admin') }}"><img src="{{ url('/panel/img/ts-avatar.jpg') }}" class="ts-avatar hidden-side" alt=""> Account <i class="fa fa-angle-down hidden-side"></i></a>
            <ul>
                <li><a href="#">My Account</a></li>
                <li><a href="#">Edit Account</a></li>
                <li><a href="{{ url('logout') }}">Logout</a></li>
            </ul>
        </li>
    </ul>
</div>