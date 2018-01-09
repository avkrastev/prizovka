<!-- Side Navbar -->
<nav class="side-navbar">
    <div class="side-navbar-wrapper">
        <div class="sidenav-header d-flex align-items-center justify-content-center">
        <div class="sidenav-header-inner text-center"><img src="{{ static_url('img/logo.jpg') }}" alt="лого" class="img-fluid rounded-circle">
            <h2 class="h5 text-uppercase">{{ userData.getOrg().firm }}</h2><span class="text-uppercase">ЧСИ {{ userData.getOrg().name }}</span>
        </div>
        <div class="sidenav-header-logo">
            <a href="/" class="brand-small text-center">
                <strong><?php echo mb_substr($userData->first_name, 0, 1); ?></strong>
                <strong class="text-primary"><?php echo mb_substr($userData->last_name, 0, 1); ?></strong>
            </a>
        </div>
        </div>
        <div class="main-menu">
        <ul id="side-main-menu" class="side-menu list-unstyled">    
            {% set activePage = router.getControllerName() %} 
            {% for menu in menus %} 
                <li {{ activePage == menu.controller ? 'class="active"' : '' }}>
                    <a href="/{{menu.controller}}"><i class="icon {{ menu.icon }}"></i><span>{{ menu.name }}</span></a>
                </li>
            {% endfor %}
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
            <div class="brand-text hidden-sm-down"><span>{{ userData.first_name }}</span>&nbsp;<strong class="text-primary">{{ userData.last_name }}</strong></div></a></div>
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <li class="nav-item"><a href="/logout" class="nav-link logout">Излез<i class="fa fa-sign-out"></i></a></li>
                </ul>
            </div>
        </div>
        </nav>
    </header>
    <div class="breadcrumb-holder">   
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Система за следене и управление на призовки</a></li>
                {% set activeSubpage = router.getActionName() %} 
                {% for menu in menus %} 
                    {% if (activePage == menu.controller) %}
                        <li class="breadcrumb-item active">
                            {% if (activeSubpage != 'index') %}
                                <a href="/{{ menu.controller }}">{{ menu.name }}</a>
                            {% else %}
                                {{ menu.name }}
                            {% endif %}
                        </li>
                        {% if menu.submenu is defined %}
                            {% for sub in menu.submenu %} 
                                {% if (activeSubpage == sub.action) %}
                                    <li class="breadcrumb-item active">{{ sub.name }}</li>
                                {% endif %}
                            {% endfor %} 
                        {% endif %}
                    {% endif %}
                {% endfor %} 
            </ul>
        </div>
    </div>
    <div class="container-fluid flash-output">
        {{ flash.output() }}
    </div>
    {{ content() }}
    <footer class="main-footer">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-right">
            <p>ЧСИ {{ userData.getOrg().name }} &copy; {{ date('Y') }} </p> 
            </div>
        </div>
        </div>
    </footer>
</div>
