<div class="page login-page">
    <div class="container">
        <div class="form-outer text-center d-flex align-items-center"> 
        <div class="form-inner">
            <img class="logo" src="{{ static_url('img/kchsi.jpg') }}" alt="лого"/>
            <div class="logo text-uppercase"><strong class="text-primary">Частен съдебен изпълнител</strong></div>
            <p>Система за управление и контрол при раздаването на призовки</p>
            {{ flash.output() }}
            {{ form('session/start', 'role': 'form', 'id': 'login-form') }}
            <div class="form-group">
                <label for="login-email" class="label-custom">Електронна поща</label>
                {{ text_field('email', 'id': 'login-email') }}
            </div>
            <div class="form-group">
                <label for="login-password" class="label-custom">Парола</label>
                {{ password_field('password', 'id': 'login-password') }}
            </div>
            {{ submit_button('Влез', 'class': 'btn btn-primary', 'id': 'login') }}
            </form>
            <a href="#" class="forgot-pass">Забравена парола</a><small>
        </div>
        </div>
    </div>
</div>