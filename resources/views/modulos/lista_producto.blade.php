@extends('base')

@section('contenido')
    @include('estructura.cabecera')

    @include('estructura.menu_lateral')

    <div class="mt-5 pt-3">
        <list-products-component  />
    </div>
@endsection
