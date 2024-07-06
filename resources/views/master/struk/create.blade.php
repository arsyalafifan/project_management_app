<?php
use App\enumVar as enum;
?>
@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h5 class="card-title text-uppercase">TAMBAH DATA</h5><hr />
            @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger alert-dismissible fade show" role="alert">{{ $error }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </p>
            @endforeach
            @endif

            @if (session()->has('message'))
                <p class="alert alert-success alert-dismissible fade show" role="alert">{{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </p>
            @endif

            <form method="POST" action="{{ route('struk.store') }}" class="form-horizontal form-material m-t-40 needs-validation" enctype="multipart/form-data">
                @csrf

                <div class="form-group row">
                    <label for="strukkode" class="col-md-12 col-form-label text-md-left">{{ __('Kode Struktur *') }}</label>

                    <div class="col-md-12">
                        <input id="strukkode" type="text" class="form-control @error('strukkode') is-invalid @enderror" name="strukkode" value="{{ (old('strukkode') ?? $strukkode) }}" maxlength="100" required autocomplete="strukkode" autofocus>

                        @error('strukkode')
                            <span class="invalid-feedback" role="alert">
                                <p class="text-danger">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="struknama" class="col-md-12 col-form-label text-md-left">{{ __('Nama Struktur *') }}</label>

                    <div class="col-md-12">
                        <input id="struknama" type="text" class="form-control @error('struknama') is-invalid @enderror" name="struknama" value="{{ old('struknama') }}" required autocomplete="name">

                        @error('struknama')
                            <span class="invalid-feedback" role="alert">
                                <p class="text-danger">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">
                            {{ __('Simpan') }}
                        </button>
                        <a href="{{ route('struk.index') }}" class="btn btn-primary waves-effect waves-light m-r-10">
                            {{ __('Index Struktur') }}
                        </a>
                        {{-- <a href="{{ route('home') }}" class="btn btn-dark waves-effect waves-light m-r-10">
                            {{ __('Home') }}
                        </a> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.custom-select').select2();

        $('#provid').select2().on('change', function() {
            if ($('#provid').val() == "") {
                $('#strukkode').val('');
            }
            else {
                var url = "{{ route('struk.nextno', ':parentid') }}"
                url = url.replace(':parentid', $('#provid').val());
                $.ajax({
                    url:url,
                    type:'GET',
                    success:function(data) {
                        $('#strukkode').val(data);
                    }
                });
            }
        }).trigger('change');
    });
</script>
@endsection
