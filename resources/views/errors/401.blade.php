@extends('errors::minimal')

@section('title', __('global.error_401_title'))
@section('code', webpasset('site/assets/img/401.png'))
@section('message', __('global.error_401_message'))
