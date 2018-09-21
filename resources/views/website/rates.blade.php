@extends('layouts.websitelayout')

@section('title')
Rates - UNILAB Bayanihan Center
@endsection

@section('content')
<div class="row">
    <h2 class="text-center">Rental Rates</h2>
    <h3>Function Halls</h3>
    <table id="tblFunctionHalls" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="col-sm-2 text-center">Function Hall</th>
                <th class="col-sm-1 text-center">Floor Area<br/>(Sq. M.)</th>
                <th class="col-sm-1 text-center">Capacity<br/>(Pax)</th>
                <th class="col-sm-1 text-center">Whole Day Rate<br/>(10 Hrs)</th>
                <th class="col-sm-1 text-center">Half Day Rate<br/>(5 Hrs)</th>
                <th class="col-sm-1 text-center">Ingress/Eggress Hourly Rate</th>
                <th class="col-sm-1 text-center">Hourly Rate in Excess of Reservation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($functionhalls as $functionhall)
            <tr>
                <td >{{ $functionhall->name }}</td>
                <td style="text-align:center" >{{ $functionhall->floorarea }}</td>
                <td style="text-align:center" >{{ $functionhall->mincapacity }} - {{ $functionhall->maxcapacity }}</td>
                <td style="text-align:right" >{{ number_format($functionhall->wholedayrate, 2) }}</td>
                <td style="text-align:right" >{{ number_format($functionhall->halfdayrate, 2) }}</td>
                <td style="text-align:right" >{{ number_format($functionhall->ineghourlyrate, 2) }}</td>
                <td style="text-align:right" >{{ number_format($functionhall->hourlyexcessrate, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>

    <h3>Meeting Rooms</h3>
    <table id="tblMeetingRooms" class="table table-bordered table-hover">
        <col>
        <col>
        <col>
        <col>
        <col>
        <colgroup span="2"></colgroup>
        <thead>
            <tr>
                <th class="col-sm-2 text-center" scope="col">Meeting Room</th>
                <th class="col-sm-1 text-center" scope="col">Floor Area <br> (Sq. M.)</th>
                <th class="col-sm-1 text-center" scope="col">Capacity <br> (Pax)</th>
                <th class="col-sm-1 text-center" scope="col">Rate per Block <br> (2 Hrs)</th>
                <th class="col-sm-1 text-center" scope="col">Ingress/Eggress<br>Hourly Rate</th>
                <th style="text-align: center" class="col-sm-2" colspan="1" scope="colgroup">Time Blocks <button type="button" data-toggle="modal" data-target="#modalTimeblock"><i class="fa fa-question"></i></button></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($meetingrooms as $meetingroom)
            <tr>
                <td>{{ $meetingroom->name }}</td>
                <td style="text-align:center">{{ $meetingroom->floorarea }}</td>
                <td style="text-align:center">{{ $meetingroom->mincapacity }} - {{ $meetingroom->maxcapacity }}</td>
                <td style="text-align:right">{{ number_format($meetingroom->rateperblock, 2) }}</td>
                <td style="text-align:right">{{ number_format($meetingroom->ineghourlyrate, 2) }}</td>
                <td style="text-align:center" class="col-sm-1">{{ $meetingroom->timeblockcode }}</td>
            </tr>
            @endforeach
            <tr>
                <td>Any 2 Room Combi From I to M</td>
                <td style="text-align:center">100.00</td>
                <td style="text-align:center">40 - 60</td>
                <td style="text-align:right">4,050.00</td>
                <td style="text-align:right">660.00</td>
                <td style="text-align:center" class="col-sm-1">F</td>
            </tr>
            <tr>
                <td>Any 3 Room Combi From I to M</td>
                <td style="text-align:center">150.00</td>
                <td style="text-align:center">60 - 90</td>
                <td style="text-align:right">5,700.00</td>
                <td style="text-align:right">990.00</td>
                <td rowspan="3" style="text-align:center; vertical-align:middle" class="col-sm-1">G</td>
            </tr>
            <tr>
                <td>Any 4 Room Combi From I to M</td>
                <td style="text-align:center">200.00</td>
                <td style="text-align:center">80 - 120</td>
                <td style="text-align:right">7,350.00</td>
                <td style="text-align:right">1,320.00</td>
                {{-- <td style="text-align:center" class="col-sm-1">G</td>
                <td style="text-align:center" class="col-sm-1">10:30PM - 12:00AM</td> --}}
            </tr>
            <tr>
                <td>Any 5 Rooms</td>
                <td style="text-align:center">237.00</td>
                <td style="text-align:center">100 - 150</td>
                <td style="text-align:right">9,000.00</td>
                <td style="text-align:right">1,650.00</td>
                {{-- <td style="text-align:center" class="col-sm-1">G</td>
                <td style="text-align:center" class="col-sm-1">10:30PM - 12:00AM</td> --}}
            </tr>
        </tbody>
    </table><br>
    
    <h3>Material/Equipments<button id="infotooltip" type="button" class="btn btn-flat btn-info" data-toggle="tooltip" data-placement="top" title="Equipments with the same Whole Day Rate, Half Day Rate, and Excess Hour Rate are charged per equipment. Thank you."><i class="fa fa-question"></i></button></h3>
    <table id="tblEquipments" class="table table-bordered table-hovered">
        <thead>
            <tr>
                <th class="col-sm-2 text-center">Material/Equipment</th>
                <th class="col-sm-1 text-center">Whole Day Rate <br> (6 - 10 Hrs)</th>
                <th class="col-sm-1 text-center">Half Day Rate <br> (1 - 5 Hrs)</th>
                <th class="col-sm-1 text-center">Excess Hour Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipments as $equipment)
            <tr>
                <td >{{ $equipment->name }}</td>
                @if ($equipment->wholedayrate == $equipment->halfdayrate && $equipment->halfdayrate == $equipment->hourlyexcessrate)
                <td class="text-center" colspan="3"> {{ number_format($equipment->wholedayrate, 2) }} {{ $equipment->description }}</td>
                @else
                <td class="text-right">{{ number_format($equipment->wholedayrate, 2) }}</td>
                <td class="text-right">{{ number_format($equipment->halfdayrate, 2) }}</td>
                <td class="text-right">{{ number_format($equipment->hourlyexcessrate, 2) }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="modalTimeblock" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-alert"></i> Information</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hovered">
                        <thead>
                            <tr><th class="col-xs-12 text-center" colspan="2">Timeblock Table</th></tr>
                            <tr><th class="col-xs-6 text-center" colspan="1">Timeblock</th><th class="col-xs-6 text-center" colspan="1">Time</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($timeblocks as $timeblock)
                            <tr>
                                <td class="text-center">{{ $timeblock->code }}</td>
                                <td class="text-center">{{ date('h:iA', strtotime($timeblock->timestart)) }} - {{ date('h:iA', strtotime($timeblock->timeend)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"> <i class="fa fa-check"></i> OK</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@endsection
