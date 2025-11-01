@extends('errors::minimal')

@section('title', __('global.error_429_title'))
@section('code', webpasset('site/assets/img/429.png'))
@section('message', __('global.error_429_message'))
