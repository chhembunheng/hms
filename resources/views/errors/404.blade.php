@extends('errors::minimal')

@section('title', __('global.error_404_title'))
@section('code', webpasset('site/assets/img/404.png'))
@section('message', __('global.error_404_message'))
