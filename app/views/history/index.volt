<section class="charts">
    <div class="container-fluid">
        <header class="clearfix"> 
            <h1 class="h3 float-left">История</h1>
        </header>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h2 class="h5 display">История на раздаванията</h2>
                    </div>
                    <div class="card-block">
                        {{ form("history/search", "class": "form-horizontal") }}
                            {% for element in form %}
                                {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
                                    {{ element }}
                                {% else %}
                                    <div class="form-group row">
                                        <label class="col-sm-4">{{ element.label() }}</label>
                                        <div class="col-sm-8">
                                            {{ element.render(['class': 'form-control']) }}
                                        </div>
                                    </div>
                                {% endif %}
                            {% endfor %}
                            <div class="form-group row">
                                <label class="col-sm-4">Период</label>
                                <div class="input-daterange input-group col-sm-8">
                                    <input type="text" class="input-sm form-control" name="start" />
                                    <span class="input-group-addon">до</span>
                                    <input type="text" class="input-sm form-control" name="end" />
                                </div>
                            </div>
                            <div class="form-group row">       
                                <div class="col-sm-8 offset-sm-4">
                                    {{ submit_button("Търсене", "class": "btn btn-primary") }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
</section>
        
            
            
        
        