
{{ content() }}

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
<!-- <div class="row">

    <div class="col-md-6">
        <div class="page-header">
            <h2>Log In</h2>
        </div>
        {{ form('session/start', 'role': 'form') }}
            <fieldset>
                <div class="form-group">
                    <label for="email">Username/Email</label>
                    <div class="controls">
                        {{ text_field('email', 'class': "form-control") }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="controls">
                        {{ password_field('password', 'class': "form-control") }}
                    </div>
                </div>
                <div class="form-group">
                    {{ submit_button('Login', 'class': 'btn btn-primary btn-large') }}
                </div>
            </fieldset>
        </form>
    </div>

    <div class="col-md-6">

        <div class="page-header">
            <h2>Don't have an account yet?</h2>
        </div>

        <p>Create an account offers the following advantages:</p>
        <ul>
            <li>Create, track and export your invoices online</li>
            <li>Gain critical insights into how your business is doing</li>
            <li>Stay informed about promotions and special packages</li>
        </ul>

        <div class="clearfix center">
            {{ link_to('register', 'Sign Up', 'class': 'btn btn-primary btn-large btn-success') }}
        </div>
    </div>

</div>
-->