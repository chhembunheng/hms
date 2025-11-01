@extends('errors::minimal')

@section('title', __('global.error_500_title'))
@section('code', webpasset('site/assets/img/500.png'))
@section('message', __('global.error_500_message'))
