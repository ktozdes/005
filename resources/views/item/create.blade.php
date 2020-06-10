@extends('includes.main')

@section('title')
    Creating Item
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
        	<h2>Создать Товар</h2>
        </div>
    </div>
    <form action="{{URL::route('item.store')}}" enctype="multipart/form-data" method="post">
    	{{csrf_field()}}
	    <div class="row">
	        <div class="col-md-8">

            	<div class="form-group">
				    <label for="item-name">Наименование: </label>
				    <input id="item-name" class="form-control" name="name" type="text" placeholder="Имя Товара" value="{{ old('name') }}">
				    @error('name')
					    <div class="badge badge-danger">{{ $message }}</div>
					@enderror
			  	</div>
            	<div class="form-group">
				    <label for="item-price">Цена: </label>
				    <input id="item-price" class="form-control" name="price" type="text" placeholder="100.00" value="{{ old('price') }}">
				    @error('price')
					    <div class="badge badge-danger">{{ $message }}</div>
					@enderror
			  	</div>

				<div class="form-group">
				  <h3>Габариты</h3>
				  <div class="input-group">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-default">Длина</span>
					  </div>
					  <input name="width" type="number" placeholder="2000" class="form-control" value="{{ old('width') }}">
					  
					  <div class="input-group-prepend">
					  	<span class="input-group-text" id="inputGroup-sizing-default">Ширина</span>
					  </div>
					  <input name="length" type="number" placeholder="300" class="form-control" value="{{ old('length') }}">

					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-default">Высота</span>
					  </div>
					  <input name="height" type="number" placeholder="50" class="form-control" value="{{ old('height') }}">
				  </div>
				  @error('width')
					    <div class="badge badge-danger">{{ $message }}</div>
				  @enderror
				  @error('length')
					    <div class="badge badge-danger">{{ $message }}</div>
				  @enderror
				  @error('height')
					    <div class="badge badge-danger">{{ $message }}</div>
				  @enderror
				</div>
				<div class="form-group">
					<div class="input-group">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroupFileAddon01">Выберите Фото</span>
					  </div>
					  <div class="custom-file">
					    <input name="file" type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
					    <label class="custom-file-label" for="inputGroupFile01">Загрузить</label>
					  </div>
					</div>
				  	@error('file')
				    	<div class="badge badge-danger">{{ $message }}</div>
				  	@enderror
				</div>
				
				<button type="submit" class="btn btn-primary">Создать</button>

		    </div>
	        <div class="col-md-4">
	        	@if (isset($attributes))
	        	<div class="row">
		        	<h3>Аттрибуты</h3>
		        	@foreach ($attributes as $attribute)
		        	<div class="form-group col-md-12">
				      <label for="attribute_{{$attribute->id}}">{{$attribute->name}}</label>
				      <select id="inputState_{{$attribute->id}}" name="attribute[{{$attribute->id}}]" class="form-control">
				        <option>Choose...</option>
				        @foreach ($attribute->value as $attributeValue)
				        	<option value="{{$attributeValue->name}}" {{old('attribute[ $attribute->id ]') == $attributeValue->name ? 'selected':'' }} >{{$attributeValue->name}}</option>
			        	@endforeach
				      </select>
				    </div>
				    @error('attribute[{{$attribute->id}}]')
				    	<div class="badge badge-danger">{{ $message }}</div>
				  	@enderror
		            @endforeach
	        	</div>
	        	@endif

	        	@if (isset($categories))
	        	<div class="row">
		        	<h3>Категории</h3>
		        	<div class="form-group col-md-12">
		        	@foreach ($categories as $category)
			        	<div class="form-check">
						  <input name="category[{{$category->id}}]" class="form-check-input" type="checkbox" id="category_{{$category->id}}">
						  <label class="form-check-label" for="category_{{$category->id}}">{{$category->name}}</label>
						</div>
		            @endforeach
		            @error('category')
				    	<div class="badge badge-danger">{{ $message }}</div>
				  	@enderror
		        	</div>
	        	</div>
	        	@endif
	        </div>
	    </div>
	</form>
@endsection
