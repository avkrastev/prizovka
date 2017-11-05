<!-- Side Navbar -->
<nav class="side-navbar">
    <div class="side-navbar-wrapper">
        <div class="sidenav-header d-flex align-items-center justify-content-center">
        <div class="sidenav-header-inner text-center"><img src="../img/logo.jpg" alt="лого" class="img-fluid rounded-circle">
            <h2 class="h5 text-uppercase">{{ user.number }}</h2><span class="text-uppercase">ЧСИ {{ user.first_name }}  {{ user.last_name }}</span>
        </div>
        <div class="sidenav-header-logo">
            <a href="/" class="brand-small text-center">
                <strong><?php echo mb_substr($user->first_name, 0, 1); ?></strong>
                <strong class="text-primary"><?php echo mb_substr($user->last_name, 0, 1); ?></strong>
            </a>
        </div>
        </div>
        <div class="main-menu">
        <ul id="side-main-menu" class="side-menu list-unstyled">                  
            <li class="active"><a href="/employees"><i class="icon ion-ios-people-outline"></i><span>Служители</span></a></li>
            <li> <a href="forms.html"><i class="icon ion-ios-paper-outline"></i><span>Призовки</span></a></li>
            <li> <a href="charts.html"><i class="icon ion-arrow-graph-up-right"></i><span>Статистика</span></a></li>
            <li> <a href="tables.html"> <i class="icon ion-ios-box-outline"> </i><span>История</span></a></li>
            <li> <a href="login.html"> <i class="icon ion-map"></i><span>Адреси</span></a></li>
        </ul>
        </div>
    </div>
</nav>
<div class="page forms-page">
    <!-- navbar-->
    <header class="header">
        <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
            <div class="navbar-header"><a id="toggle-btn" href="/" class="menu-btn"><i class="icon ion-android-menu"> </i></a><a href="/" class="navbar-brand">
            <div class="brand-text hidden-sm-down"><span>{{ user.first_name }}</span>&nbsp;<strong class="text-primary">{{ user.last_name }}</strong></div></a></div>
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <li class="nav-item"><a href="/session/end" class="nav-link logout">Излез<i class="fa fa-sign-out"></i></a></li>
                </ul>
            </div>
        </div>
        </nav>
    </header>
    <div class="breadcrumb-holder">   
        <div class="container-fluid">
            <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Система за следене и управление на призовки</a></li>
            <li class="breadcrumb-item active">Служители</li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        {{ flash.output() }}
        {{ content() }}
    </div>
    <footer class="main-footer">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-right">
            <p>ЧСИ {{ user.first_name }}  {{ user.last_name }} &copy; {{ date('Y') }} </p> 
            </div>
        </div>
        </div>
    </footer>
</div>
