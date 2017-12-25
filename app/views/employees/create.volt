<section class="forms">
    <div class="container-fluid">
        <header> 
            <h1 class="h3 display">Добавяне на нов служител</h1>
        </header>
        <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-block">
                    {{ form('employees/create', 'id': 'employeesForm', 'class': 'form-horizontal', 'autocomplete': 'off') }}
                    <div class="form-group row">
                        {{ form.label('first_name', ['class': 'col-sm-3 form-control-label']) }}
                        <div class="col-sm-9">
                            {{ form.render('first_name', ['class': 'form-control']) }}
                            <span class="help-block-none form-control-feedback">Моля въведете име</span>
                        </div>  
                    </div>
                    <div class="form-group row">
                        {{ form.label('last_name', ['class': 'col-sm-3 form-control-label']) }}
                        <div class="col-sm-9">
                            {{ form.render('last_name', ['class': 'form-control']) }}
                            <span class="help-block-none form-control-feedback">Моля въведете фамилия</span>
                        </div>   
                    </div>
                    <div class="form-group row">
                        {{ form.label('email', ['class': 'col-sm-3 form-control-label', 'autocomplete': 'off']) }}
                        <div class="col-sm-9">
                            {{ form.render('email', ['class': 'form-control']) }}
                            <span class="help-block-none form-control-feedback">Електронната поща е задължително поле</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ form.label('password', ['class': 'col-sm-3 form-control-label']) }}
                        <div class="col-sm-9">
                            {{ form.render('password', ['class': 'form-control']) }}
                            <span class="help-block-none form-control-feedback">Паролата е задължително поле</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ form.label('type', ['class': 'col-sm-3 form-control-label']) }}
                        <div class="col-sm-9 select">
                            {{ form.render('type', ['class': 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ form.label('active', ['class': 'col-sm-3 form-control-label']) }}
                        <div class="col-sm-9">
                            <div class="i-checks">
                                {{ form.render('active') }}
                            </div>
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                        <div class="col-sm-4 offset-sm-3">
                        {{ submit_button('Запази', 'class': 'btn btn-primary') }}
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>
