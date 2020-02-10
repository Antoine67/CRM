<h5>Variables :</h5>
@foreach ($vars as $var)
    <div class="form-group row">
        <label for="var-{{ $var->id }}" class="col-sm-2 col-form-label">{{ $var->name }}</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="var-{{ $var->id }}" placeholder="Valeur">
        </div>
  </div>
@endforeach

