
<section class="charts">
    <div class="container-fluid">
        <header class="clearfix"> 
            <h1 class="h3 float-left">Редактиране на призовка</h1>
            <a href="{{ url('subpoenas') }}" class="btn btn-primary float-right">
                <i class="icon ion-chevron-left"></i>Обратно към списъка с призовки
            </a>
        </header>
        <div class="row editSubpoenas">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-sm-4">
                                {{ form('subpoenas/save', 'role': 'form', 'id': 'addressesForm') }}
                                    {{ form.render('id') }}
                                    {{ form.render('latitude') }}
                                    {{ form.render('longitude') }}
                                    <input type="hidden" id="old_assignment" name="old_assignment" value="{{ address.assigned_to }}"/>
                                    <div class="form-group">
                                        {{ form.label('case_number') }}
                                        {{ form.render('case_number', ['class': 'form-control']) }}
                                    </div>
                                    <div class="form-group">
                                        {{ form.label('reference_number') }}
                                        {{ form.render('reference_number', ['class': 'form-control']) }}
                                    </div>  
                                    <div class="form-group">
                                        {{ form.label('address') }}
                                        {{ form.render('address', ['class': 'form-control', 'id': 'pac-input']) }}
                                        <span class="help-block-none form-control-feedback">Адресът е задължително поле</span>
                                    </div>
                                    <div class="form-group">
                                        {{ form.label('assigned_to', ['for': 'assigned_to']) }}
                                        <div class="select">
                                            {{ form.render('assigned_to', ['class': 'form-control']) }}
                                        </div>
                                        <span class="help-block-none form-control-feedback">Служителят е задължително поле</span>
                                    </div>
                                    <div class="form-group">       
                                        <input type="submit" id="assignAddress" value="Запази промените" class="btn btn-primary">
                                    </div>
                                    <div id="qrcode">
                                        <a href="#" id="downloadQR" download="">
                                            <img src="" alt="" title="QR код" /><br>
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-8">
                                <div id="map"></div>
                                <div id="infowindow-content">
                                    <img src="" width="16" height="16" id="place-icon">
                                    <span id="place-name" class="title"></span><br>
                                    <span id="place-address"></span>
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="serviceFields">
                            <div class="row">
                                <label class="col-sm-4 col-form-label">Обновенa от:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-static updated_by">{{ address.updated_by }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label">Обновенa на:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-static updated_at">{{ address.updated_at }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label">Създаденa от:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-static created_by">{{ address.created_by }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label">Създаденa на:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-static created_at">{{ address.created_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>