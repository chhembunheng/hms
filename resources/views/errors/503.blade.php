@extends('errors::minimal')

@section('title', __('global.error_503_title'))
@section('code', webpasset('site/assets/img/503.png'))
@section('message', __('global.error_503_message'))
