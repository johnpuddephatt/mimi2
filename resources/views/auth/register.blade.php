@extends('layouts.static')

@section('content')
 <section class="section is-medium">
   <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <registration-form admin="{{ $admin ?? null }}" course="{{ $course ?? null }}"></registration-form>
        </div>
    </div>
  </section>
@endsection
