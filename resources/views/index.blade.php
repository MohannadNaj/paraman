@extends('parameters::shared.parameters-layout')

@section('content')
  <parameters ref="all-parameters" :parameters_list='{!! $parameters->toJson(JSON_HEX_APOS) !!}'></parameters>
@endsection

