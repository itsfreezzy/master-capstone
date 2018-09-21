<div class="container">
    <div class="row text-center">
        <h2>Reservation Form</h2>
    </div>
    <form class="form-horizontal">
        @csrf
        {{--  Date Filed  --}}
        <div class="row">
            <div class="form-group">
                <label for="DateFiled" class="control-label col-sm-9">Date Filed:</label>
                <div class="col-sm-2">
                    <input style="width:120%" type="text" name="DateFiled" id="datePicker" class="form-control form-horizontal" value="{{ date('F d, Y', strtotime($reservation->datefiled)) }}" readonly>
                </div>
            </div>
        </div>
        
        {{--  Date of Event  --}}
        <div class="row">
            <div class="form-group">
                <label for="EventDate" class="control-label col-sm-2">Date of Event:*</label>
                <div class="col-sm-10">
                    <input style="width: 93%" type="text" name="EventDate" id="datePicker" class="form-control form-horizontal" value="{{ date('F d, Y', strtotime($reservation->eventdate)) }}" readonly>
                </div>
            </div>
        </div>

        {{--  Title of Event  --}}
        <div class="row">
            <div class="form-group">
                <label for="EventTitle" class="control-label col-sm-2">Title of Event:*</label>
                <div class="col-sm-10">
                    <input style="width: 93%" type="text" name="EventTitle" id="" class="form-control form-horizontal" value="{{ $reservation->eventtitle }}" readonly>
                </div>
            </div>
        </div>

        {{--  Preferred Function Room  --}}
        <div class="row">
            <div class="form-group">
                <label for="PrefFuncRoom" class="control-label col-sm-2">Preferred Function Room/s:*</label>
                <div class="col-sm-10">
                    <select name="PrefFuncRooms[]" style="width: 93%" id="PrefFuncRoom" class="form-control form-horizontal" multiple disabled>
                        <optgroup label="Function Halls">   
                            @foreach ($functionhalls as $functionhall)
                            @foreach ($eventvenues as $eventvenue)
                            @if ($functionhall->code == $eventvenue->venuecode)
                            <option value="{{ $functionhall->code }}" selected>{{ $functionhall->name }}</option>
                            @endif
                            @endforeach
                            @endforeach
                        </optgroup>
                        <optgroup label="Meeting Rooms">    
                            @foreach ($meetingrooms as $meetingroom)
                            @foreach ($eventvenues as $eventvenue)
                            @if ($meetingroom->code == $eventvenue->venuecode)
                            <option value="{{ $meetingroom->code }}" selected>{{ $meetingroom->name }}</option>
                            @endif
                            @endforeach
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

        {{--  Caterer  --}}
        <div class="row">
            <div class="form-group">
                <label for="Caterer" class="control-label col-sm-2">Caterer Name:*</label>
                <div class="col-sm-10">
                    {{-- <input style="width: 93%; padding-bottom: 0%" type="text" name="CatererName" id="CatererName" class="form-control form-horizontal"> --}}
                    <select name="CatererName" id="CatererName" style="width: 93%" class="form-control form-horizontal" disabled>
                        <option value="{{$reservationinfo->caterer}}" selected>{{$reservationinfo->caterer}}</option>
                    </select>
                </div>
            </div>
        </div>
        <label class="radio-inline" style="padding-left: 18%; padding-top: 0%; display: none"><input checked type="radio" id="accredited" name="isAccredited" value="1" >Accredited</label>
        <label class="radio-inline" style="padding-top: 0%; display: none"><input type="radio" id="notaccredited" name="isAccredited" value="0" >Non-Accredited</label>
        <label class="radio-inline" style="padding-left: 18%; padding-top: 0%"><input checked type="radio" id="dispaccredited" value="1" disabled>Accredited</label>
        <label class="radio-inline" style="padding-top: 0%;"><input type="radio" id="dispnotaccredited" value="0" disabled>Non-Accredited</label>

        {{--  Nature of Event  --}}
        <div class="row" style="padding-top: 2%">
            <div class="form-group">
                <label for="EventNature" class="control-label col-sm-2">Nature of Event:*</label>
                <div class="col-sm-9">
                    <select name="EventNature[]" id="EventNature" class="form-control form-horizontal" multiple disabled style="width: 100%">
                        {{-- <option value="">-- SELECT --</option> --}}
                        @foreach((array)(explode(",", $reservationinfo->eventnature)) as $input)
                        <option value="{{ $input }}" selected>{{ $input }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{--  Number of Attendees  --}}
        <div class="row" style="padding-top: 1%">
            <div class="form-group">
                {{--  <label for="EventTitle" class="control-label col-sm-2">Number of Attendees:*</label>  --}}
                <p class="control-label col-sm-2">Number of Attendees:*</p>
                <div class="col-sm-10">
                    <input style="width: 20%" type="number" name="NumAttendees" id="NumAttendees" class="form-control form-horizontal" min="1" max="999" value="{{ $reservationinfo->numofattendees }}" disabled>
                </div>
            </div>
        </div>

        {{--  Time  --}}
        <div class="row">
            <div class="form-group">
                {{--  <label for="EventTitle" class="control-label col-sm-2">Start Time:*</label>  --}}
                <p class="control-label col-sm-2">Start Time:*</p>
                <div class="col-sm-2">
                    <input style="" type="time" name="TimeStart" id="" class="form-control form-horizontal" value="{{ $reservationinfo->timestart }}" disabled>
                </div>


                {{--  <label for="EventTitle" class="control-label col-sm-2">End Time:*</label>  --}}
                <p class="control-label col-sm-2">End Time:*</p>
                <div class="col-sm-2">
                    <input style="" type="time" name="TimeEnd" id="" class="form-control form-horizontal" value="{{ $reservationinfo->timeend }}" disabled>
                </div>
            </div>
        </div>

        {{--  Ingress/Eggress Time  --}}
        <div class="row">
            <div class="form-group">
                {{--  <label for="EventTitle" class="control-label col-sm-2">Ingress Time:*</label>  --}}
                <p class="control-label col-sm-2">Ingress Time:*</p>
                <div class="col-sm-2">
                    <input style="" type="time" name="IngressTime" id="" class="form-control form-horizontal" value="{{ $reservationinfo->timeingress }}" disabled>
                </div>


                {{--  <label for="EventTitle" class="control-label col-sm-2">Eggress Time:*</label>  --}}
                <p class="control-label col-sm-2">Eggress Time:*</p>
                <div class="col-sm-2">
                    <input style="" type="time" name="EggressTime" id="" class="form-control form-horizontal" value="{{ $reservationinfo->timeeggress }}" disabled>
                </div>
            </div>
        </div>

        {{--  Ingress/Eggress Date  --}}
        <div class="row">
            <div class="form-group">
                {{--  <label for="EventTitle" class="control-label col-sm-2">Ingress Date:</label>  --}}
                <p class="control-label col-sm-2">Ingress Date:</p>
                <div class="col-sm-2">
                    <input style="" type="date" name="IngressDate" id="" class="form-control form-horizontal" value="{{ $reservationinfo->dateingress }}" disabled>
                </div>


                {{--  <label for="EventTitle" class="control-label col-sm-2">Eggress Date:</label>  --}}
                <p class="control-label col-sm-2">Eggress Date:</p>
                <div class="col-sm-2">
                    <input style="" type="date" name="EggressDate" id="" class="form-control form-horizontal" value="{{ $reservationinfo->dateeggress }}" disabled>
                </div>
            </div>
        </div>

        {{--  Set Up  --}}
        <div class="row">
            <div class="form-group">
                <label for="SetupType" class="control-label col-sm-2">Physical Set-up:*</label>
                <div class="col-sm-8">
                    <select name="EventSetup" id="EventSetup" class="form-control form-horizontal" disabled>
                        <option value="{{ $reservationinfo->eventsetup }}" selected>{{ $reservationinfo->eventsetup }}</option>
                    </select>
                </div>
                
                {{-- <button type="button" class="btn col-sm-1"> <i class="fa fa-plus"></i> Add</button> --}}
            </div>
        </div>

        {{--  Audio/Visual  --}}
        <div class="row">
            <div class="form-group">
                <label style="margin-left: 4%;" class="control-label">Audio/Visual & Other Requirements:* </label>
            </div>
        </div>

        <div class="row">
            <div id="listEquipment" class="form-group">
                <div class="row">
                    <label id="lbl1" for="" class="col-sm-offset-1 col-sm-3 control-label">Equipment</label>
                    <label id="lbl2" for="" class="col-sm-offset-1 col-sm-1 control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity</label>
                    <label id="lbl3" for="" class="col-sm-offset-1 col-sm-1 control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price</label>
                    <label class="col-sm-offset-2 col-sm-2" id="lbl4" style="visibility:hidden">asdzxcxzczxczcxzczxczxczxxczxczxczczxxczczczxcczxczcz</label>
                </div>

                @foreach($equipments as $equipment)
                @foreach($eventequipments as $eventequipment)
                @if ($equipment->code == $eventequipment->equipmentcode)
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-3">
                            <input class="form-control" type="text" value="{{$equipment->name}}" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" value="{{$eventequipment->qty}}" type="number" name="quantity[]" min="1" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" readonly value="{{$eventequipment->totalprice}}">
                        </div>
                    </div>
                @endif
                @endforeach
                @endforeach

                <div class="row">
                    <label class="col-sm-offset-6 col-sm-1 control-label">Total: P</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" readonly value="{{$grandtot}}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Event Organizer --}}
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-2">Event Organizer:*</label>
                <div class="col-sm-10" >
                    <input style="width:93%;" type="text" name="EventOrganizer" id="" class="form-control form-horizontal" value="{{ $reservation->eventorganizer }}" readonly>
                </div>
            </div>
        </div>

        {{-- Event Organizer Contact Number --}}
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-2">Contact No:*</label>
                <div class="col-sm-10" >
                    <input style="width:93%;" type="text" name="EventOrganizerContactNo" id="" class="form-control form-horizontal" value="{{ $reservation->eocontactno }}" readonly>
                </div>
            </div>
        </div>

        {{-- Event Organizer Email--}}
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-2">Email:*</label>
                <div class="col-sm-10" >
                    <input style="width:93%;" type="text" name="EventOrganizerEmail" id="" class="form-control form-horizontal" value="{{ $reservation->eoemail }}" readonly>
                </div>
            </div>
        </div>

        <hr>{{-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------}}

        {{--  Contact Persons  --}}
        <div class="row" style="padding-top: 0">
            <div class="form-group">
                <label class="control-label col-sm-2">Contact Persons: </label>
            </div>
        </div>

        {{--  PrimSec Contact  --}}
        <div class="row">
            <div class="form-group">
                <p class="control-label col-sm-2">Primary Contact:*</p>
                <div class="col-sm-4">
                    <input type="text" name="primcontactinfo[name]" id="" class="form-control form-horizontal" value="{{ $contacts[0]['name'] }}" disabled>
                </div>

                <p class="control-label col-sm-2">Secondary Contact:</p>
                <div class="col-sm-3" >
                    <input type="text" name="seccontactinfo[name]" id="" class="form-control form-horizontal" value="{{ (count($contacts) > 1 ? $contacts[1]['name'] : '') }}" disabled>
                </div>
            </div>
        </div>

        {{--  PrimSec TelNo  --}}
        <div class="row">
            <div class="form-group">
                <p class="control-label col-sm-2">Telephone Number:*</p>
                <div class="col-sm-4">
                    <input type="text" name="primcontactinfo[telno]" id="" class="form-control form-horizontal" value="{{ $contacts[0]['telno'] }}" disabled>
                </div>

                <p class="control-label col-sm-2">Telephone Number:</p>
                <div class="col-sm-3">
                    <input type="text" name="seccontactinfo[telno]" id="" class="form-control form-horizontal" value="{{ (count($contacts) > 1 ? $contacts[1]['telno'] : '') }}" disabled>
                </div>
            </div>
        </div>

        {{--  PrimSec MobNo  --}}
        <div class="row">
            <div class="form-group">
                <p class="control-label col-sm-2">Mobile Number:*</p>
                <div class="col-sm-4">
                    <input type="text" name="primcontactinfo[mobno]" id="" class="form-control form-horizontal" value="{{ $contacts[0]['mobno'] }}" disabled>
                </div>

                <p class="control-label col-sm-2">Mobile Number:</p>
                <div class="col-sm-3">
                    <input type="text" name="seccontactinfo[mobno]" id="" class="form-control form-horizontal"  value="{{ (count($contacts) > 1 ? $contacts[1]['mobno'] : '') }}" disabled>
                </div>
            </div>
        </div>

        {{--  PrimSec Email  --}}
        <div class="row">
            <div class="form-group">
                <p class="control-label col-sm-2">Email:*</p>
                <div class="col-sm-4">
                    <input type="text" name="primcontactinfo[email]" id="" class="form-control form-horizontal"  value="{{ $contacts[0]['email'] }}" disabled>
                </div>

                <p class="control-label col-sm-2">Email:</p>
                <div class="col-sm-3">
                    <input type="text" name="seccontactinfo[email]" id="" class="form-control form-horizontal"  value="{{ (count($contacts) > 1 ? $contacts[1]['email'] : '') }}" disabled>
                </div>
            </div>
        </div>

        {{--  Home/Company Address  --}}
        <div class="row" style="padding-bottom: 0%">
            <div class="form-group">
                <p class="control-label col-sm-2">Home/Company Address:*</p>
                <div class="col-sm-4">
                    <input type="text" name="primcontactinfo[address]" id="" class="form-control form-horizontal"  value="{{ $contacts[0]['address'] }}" disabled>
                </div>
                
                <p class="control-label col-sm-2">Home/Company Address:</p>
                <div class="col-sm-3">
                    <input type="text" name="seccontactinfo[address]" id="" class="form-control form-horizontal" value="{{ (count($contacts) > 1 ? $contacts[1]['address'] : '') }}" disabled>
                </div>
            </div>
        </div>
    </form>
</div>
