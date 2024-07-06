<?php
use App\enumVar as enum;
$view = ($prov === '2') ? 'required' : 'disabled' ;
?>
@extends('layouts.master')

@section('content')
<style>
    #ijazah-preview-container,
    #ktp-preview-container {
        display: none;
    }
</style>
<div class="card">
    <div class="card-body p-4">
        <h5 class="card-title text-uppercase">TAMBAH DATA</h5>
        <hr />
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

        <form method="POST" action="{{ route('ijazah.store') }}"
            class="form-horizontal form-material m-t-40 needs-validation" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
                <input type="hidden" name="sekolahid" id="sekolahid" value="{{ old('sekolahid',$sekolah) }}">
                <input type="hidden" name="provinsiid" id="provinsiid" value="{{ old('provinsiid',$prov) }}">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="namasiswa">Nama Siswa</label>
                        <input type="text" class="form-control @error('namasiswa') is-invalid @enderror" id="namasiswa"
                            name="namasiswa" value="{{ old('namasiswa') }}" autofocus required>
                        @error('namasiswa')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="tempat_lahir">Tempat</label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                            id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" autofocus required>
                        @error('tempat_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir"
                            name="tgl_lahir" value="{{ old('tgl_lahir') }}" autofocus required>
                        @error('tgl_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="namaortu">Nama Orang Tua</label>
                        <input type="text" class="form-control @error('namaortu') is-invalid @enderror" id="namaortu"
                            name="namaortu" value="{{ old('namaortu') }}" autofocus required>
                        @error('namaortu')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="nis">Nomor Induk Siswa</label>
                        <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis"
                            value="{{ old('nis') }}" autofocus required>
                        @error('nis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="noijazah">Nomor Ijazah</label>
                        <input type="text" class="form-control @error('noijazah') is-invalid @enderror" id="noijazah"
                            name="noijazah" value="{{ old('noijazah') }}" autofocus required>
                        @error('noijazah')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="tgl_lulus">Tanggal Kelulusan</label>
                        <input type="date" class="form-control @error('tgl_lulus') is-invalid @enderror" id="tgl_lulus"
                            name="tgl_lulus" value="{{ old('tgl_lulus') }}" autofocus required>
                        @error('tgl_lulus')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="namasekolah">Nama Sekolah</label>
                        <input type="text" class="form-control @error('namasekolah') is-invalid @enderror" id="namasekolah"
                            name="namasekolah" value="{{ $prov == enum::PROVINSI_KEPRI ? $namasekolah : old('namasekolah') }}" autofocus {{ $view }}>
                        @error('namasekolah')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="namaprov">Provinsi</label>
                        <input type="text" class="form-control @error('namaprov') is-invalid @enderror" id="namaprov" name="namaprov"
                            value="{{ $prov == enum::PROVINSI_KEPRI ? $namaprov : old('namaprov') }}" autofocus {{ $view }}>
                        @error('namaprov')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="namakab">Kabupatan/Kota</label>
                        <input type="text" class="form-control @error('namakab') is-invalid @enderror" id="namakab"
                            name="namakab" value="{{ $prov == enum::PROVINSI_KEPRI ? $namakab : old('namakab') }}" autofocus {{ $view }}>
                        @error('namakab')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="namakec">Kecamatan</label>
                        <input type="text" class="form-control @error('namakec') is-invalid @enderror" id="namakec"
                            name="namakec" value="{{ $prov == enum::PROVINSI_KEPRI ? $namakec : old('namakec') }}" autofocus {{ $view }}>
                        @error('namakec')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="file_ijazah" class="form-label">Upload Ijazah</label><br><span class="small text-danger">*File PDF | max : 5 MB</span>
                        <input class="form-control  @error('file_ijazah') is-invalid @enderror" type="file" id="file_ijazah" name="file_ijazah" required>
                        @error('file_ijazah')
                        <div class="invalid-feedback">
                            {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-12">
                        <div id="ijazah-preview-container">
                            <iframe id="ijazah-preview" width="100%" height="600px"></iframe>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row mb-0">
                        <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">
                            {{ __('Simpan') }}
                        </button>
                        <a href="{{ route('ijazah.index') }}" class="btn btn-primary waves-effect waves-light m-r-10">
                            {{ __('Index ijazah') }}
                        </a>
                        {{-- <a href="{{ route('home') }}" class="btn btn-dark waves-effect waves-light m-r-10">
                        {{ __('Home') }}
                        </a> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    // cek extentions flie
    document.addEventListener('DOMContentLoaded', function () {
        const ijazahInput = document.getElementById('file_ijazah');
        const ijazahPreviewContainer = document.getElementById('ijazah-preview-container');
        const ijazahPreview = document.getElementById('ijazah-preview');

        ijazahInput.addEventListener('change', function () {
            const file = ijazahInput.files[0];

            if (file) {
                if (file.type === 'application/pdf') {
                    const fileReader = new FileReader();

                    fileReader.onload = function (e) {
                        ijazahPreview.src = e.target.result;
                        ijazahPreviewContainer.style.display =
                            'block'; // Tampilkan kontainer saat sudah ada file
                    };

                    fileReader.readAsDataURL(file);
                } else {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Ekstensi file tidak sesuai, silakan upload file dengan ekstensi: PDF.',
                        icon: 'info',
                    });
                    
                    ijazahInput.value = ''; // Reset the input value
                    ijazahPreviewContainer.style.display = 'none';
                }

            } else {
                ijazahPreviewContainer.style.display =
                    'none'; // Sembunyikan kontainer jika tidak ada file
            }
        });
    });
</script>
@endsection
