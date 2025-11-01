@extends('errors::minimal')

@section('title', __('global.error_403_title'))
@section('code', webpasset('site/assets/img/403.png'))
@section('message', __('global.error_403_message'))
