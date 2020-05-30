@extends('layout')

@section('page-body')
    <!-- provide the csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="map_holder">
      <div id="map"></div>
    </div>

@endsection

@push('scripts')
<script type = "text/javascript">
   var hospital = JSON.parse( JSON.stringify( {!! json_encode($hospitals) !!} ) );
</script>
@endpush
