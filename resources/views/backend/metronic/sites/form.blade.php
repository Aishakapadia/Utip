<!-- Removing Autocomplete -->
<input style="opacity: 0; position: absolute;">
<input type="password" style="opacity: 0; position: absolute;">


<div class="form-body">
    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Site Title <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Site Title']) }}
            @if($errors->has('title')) <span class="help-block"> {{ $errors->first('title') }} </span> @endif
        </div>
    </div>
    {{--<div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">--}}
        {{--<label class="control-label col-md-3">Slug <span class="required" aria-required="true">*</span></label>--}}
        {{--<div class="col-md-9">--}}
            {{--{{ Form::text('slug', null, ['id' => 'slug', 'class' => 'form-control', 'placeholder' => 'Slug', 'readonly' => true]) }}--}}
            {{--@if($errors->has('slug')) <span class="help-block"> {{ $errors->first('slug') }} </span> @endif--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="form-group {{ $errors->has('site_type_id') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Site Type <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('site_type_id', $sites, null, ['id' => 'site_type_id', 'class' => 'form-control']) }}
            @if($errors->has('site_type_id')) <span class="help-block"> {{ $errors->first('site_type_id') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('material_type') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Material Type <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('material_type', ['RM/PM' => 'RM/PM', 'PM' => 'PM', 'RM' => 'RM', ], null, ['id' => 'material_type', 'class' => 'form-control']) }}
            @if($errors->has('material_type')) <span class="help-block"> {{ $errors->first('material_type') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Description </label>
        <div class="col-md-9">
            {{ Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control']) }}
            @if($errors->has('description')) <span class="help-block"> {{ $errors->first('description') }} </span> @endif
        </div>
    </div>
    <div class="form-group last">
        <label class="control-label col-md-3">Status <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('active', [1 => 'Active', 0 => 'Inactive'], null, ['id' => 'active', 'class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('sort') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Sort <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::number('sort', isset($page->sort) ? $page->sort : 0, ['id' => 'sort', 'class' => 'form-control', 'min' => '0']) }}
            <span class="help-block"> Please enter sort value, if you would like to set order priority. </span>
            @if($errors->has('sort')) <span class="help-block"> {{ $errors->first('sort') }} </span> @endif
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green"><i class="fa fa-check"></i> Save</button>
            {{ link_to_route('site-manage', 'Cancel', [], ['class' => 'btn default']) }}
        </div>
    </div>
</div>
