<section class="forms">
    <div class="container-fluid">
        <header> 
            <h1 class="h3 display">Редактиране на служител</h1>
        </header>
        <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-block">
                    {{ form('employees/save', 'role': 'form', 'id': 'employeesForm', 'class': 'form-horizontal', 'onbeforesubmit': 'return false') }}
                    {% for element in form %}
                        {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
                            {{ element }}
                        {% elseif is_a(element, 'Phalcon\Forms\Element\Text') or is_a(element, 'Phalcon\Forms\Element\Select') or is_a(element, 'Phalcon\Forms\Element\Password') %}
                            <div class="form-group">
                                <div class="form-group row">
                                    {{ element.label(['class': 'col-sm-3 form-control-label']) }}
                                    <div class="col-sm-9">
                                        {{ element.render(['class': 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        {% elseif is_a(element, 'Phalcon\Forms\Element\Check') %}
                            <div class="form-group row">
                                {{ element.label(['class': 'col-sm-3 form-control-label']) }}
                                <div class="col-sm-9">
                                    <div class="i-checks">
                                        {% set value = element.getValue()  %}                                  
                                        {{ value == 1 ? check_field('active', 'value' : value, 'checked' : 'checked') : check_field('active', 'value' : value) }}
                                    </div>
                                </div>
                            </div>
                        {% endif %}
                    {% endfor %}
                    <div class="line"></div>
                    <div class="form-group row">
                        <div class="col-sm-4 offset-sm-3">
                        {{ submit_button('Запази', 'class': 'btn btn-primary', 'onclick': 'return Employee.validate();') }}
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>
