@extends('layouts.websitelayout')

@section('title')
Accredited Caterers - UNILAB Bayanihan Center
@endsection

@section('content')
<div class="row">
    <h2 class="text-center">Accredited Caterers</h2>
    <table id="caterersTbl" class="table table-bordered table-hover">
        <thead>
            <th class="text-center col-sm-2">Caterer</th>
            <th class="text-center col-sm-3">Address</th>
            <th class="text-center col-sm-3">Contact No.</th>
            <th class="text-center col-sm-2">Email Address</th>
            <th class="text-center col-sm-2">Contact Person</th>
        </thead>
        <tbody>
            @foreach ($caterers as $caterer)
            <tr>
                <td>{{$caterer->name}}</td>
                <td>{{$caterer->address}}</td>

                <td>
                @foreach($catcontacts as $contact)
                @if ($caterer->id == $contact->catererid)
                    {{$contact->contactno}} ||
                @endif
                @endforeach
                </td>
                
                <td>
                @foreach($catemails as $email)
                @if ($caterer->id == $email->catererid)
                    {{$email->email}} <br>
                @endif
                @endforeach
                </td>

                <td>
                @foreach($catcontactpersons as $person)
                @if ($caterer->id == $person->catererid)
                    {{$person->person}} <br>
                @endif
                @endforeach
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function () {
    $('#caterersTbl').DataTable({
        pageLength: 25
    });
  } );
</script>
@endsection