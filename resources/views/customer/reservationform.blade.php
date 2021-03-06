<div class="container-fluid">
    <div class="row text-center">
        <h2>Reservation Form</h2>
    </div>
    <form class="form-horizontal" action="{{ route('client.reservationform.submit') }}" method="post" >
        @csrf
        {{--  Date Filed  --}}
        <div class="row">
            <div class="form-group">
                <label for="DateFiled" class="control-label col-sm-9">Date Filed:</label>
                <div class="col-sm-2">
                    <input style="width:120%" type="date" name="DateFiled" id="DateFiled" class="form-control form-horizontal" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
            </div>
        </div>
        
        {{--  Date of Event  --}}
        <div class="row">
            <div class="form-group">
                <label for="EventDate" class="control-label col-sm-2">Date of Event:*</label>
                <div class="col-sm-10">
                    <input style="width: 93%" type="date" name="EventDate" id="EventDate" class="form-control form-horizontal" value="{{ old('EventDate') ? old('EventDate') : date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d', strtotime('+3 months', strtotime(date('Y-m-d'))))))) }}" min="{{ date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d', strtotime('+3 months', strtotime(date('Y-m-d'))))))) }}" required>
                </div>

                @if ($errors->has('EventDate'))
                    <div class="col-sm-10 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EventDate') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{--  Title of Event  --}}
        <div class="row">
            <div class="form-group">
                <label for="EventTitle" class="control-label col-sm-2">Title of Event:*</label>
                <div class="col-sm-10">
                    <input style="width: 93%" type="text" name="EventTitle" id="" class="form-control form-horizontal" value="{{ old('EventTitle') }}" required>
                </div>
                
                @if ($errors->has('EventTitle'))
                    <div class="col-sm-10 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EventTitle') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{--  Preferred Function Room  --}}
        <div class="row">
            <div class="form-group">
                <label for="PrefFuncRoom" class="control-label col-sm-2">Preferred Function Room/s:*</label>
                <div class="col-sm-10">
                    <select style="width: 93%" id="funcroomtype" class="form-control form-horizontal" name="frtype" value="{{ old('frtype') }}">
                        <option value="">SELECT DESIRED FUNCTION ROOM TYPE</option>
                        <option value="FH" {{ old('frtype') == 'FH' ? 'selected' : '' }}>Function Hall</option>
                        <option value="MR" {{ old('frtype') == 'MR' ? 'selected' : '' }}>Meeting Room</option>
                    </select>
                </div>

                @if ($errors->has('PrefFuncRooms'))
                    <div class="col-sm-10 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('PrefFuncRooms') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="row" id="functionhalls" style="display:none">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <select name="PrefFuncRooms[]" onchange="validate()" style="width: 93%" id="preffh" class="form-control form-horizontal" multiple disabled >
                        @foreach ($functionhalls as $functionhall)
                        <option data-maxcap="{{ $functionhall->maxcapacity }}" data-mincap="{{ $functionhall->mincapacity }}" value="{{ $functionhall->code }}">{{ $functionhall->name }} || {{ $functionhall->mincapacity }} - {{ $functionhall->maxcapacity }} pax</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row" id="meetingroomblock"  style="display:none">
            <div class="form-group" id="mrtimeblock" style="display:none" >
                <div class="col-sm-offset-2 col-sm-10">
                    <select style="width: 93%" id="timeblock" class="form-control form-horizontal" name="tblock">
                        <option value="">SELECT DESIRED TIMEBLOCK</option>
                        @foreach ($timeblocks as $tb)
                        <option value="{{ $tb->code }}" data-timestart="{{ $tb->timestart }}" data-timeend="{{ $tb->timeend }}">{{ $tb->code }} | {{ date('h:i:s A', strtotime($tb->timestart)) }} - {{ date('h:i:s A', strtotime($tb->timeend)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group" id="meetingrooms">
                <div class="col-sm-offset-2 col-sm-10">
                    <select name="PrefFuncRooms[]" onchange="validate()" style="width: 93%" id="prefmr" class="form-control form-horizontal" multiple disabled>
                        @foreach ($meetingrooms as $meetingroom)
                        <option data-maxcap="{{ $meetingroom->maxcapacity }}" data-mincap="{{ $meetingroom->mincapacity }}" data-id="{{ $meetingroom->timeblockcode }}" value="{{ $meetingroom->code }}">{{ $meetingroom->name }} || {{ $meetingroom->mincapacity }} - {{ $meetingroom->maxcapacity }} pax</option>
                        @endforeach
                        @foreach ($meetrmdiscount as $mr)
                        <option data-id="{{ $mr->timeblockcode }}" data-maxcap="{{ $mr->maxcapacity }}" data-mincap="{{ $mr->mincapacity }}" value="{{ $mr->code }}">{{ $mr->name }} || {{ $mr->mincapacity }} - {{ $mr->maxcapacity }} pax</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{--  Caterer  --}}
        <div class="row">
            <div class="form-group">
                <label for="Caterer" class="control-label col-sm-2">Caterer Name:*</label>
                <div class="col-sm-10">
                    <select name="CatererName" id="CatererName" style="width: 93%" class="form-control form-horizontal" required>
                        @foreach ($caterers as $caterer)
                        <option value="{{ $caterer->name }}">{{ $caterer->name }}</option>
                        @endforeach
                    </select>

                    <select name="CatererNameData" id="CatererNameData" style="width: 93%; display:none" class="form-control form-horizontal" disabled>
                        @foreach ($caterers as $caterer)
                        <option value="{{ $caterer->name }}">{{ $caterer->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('CatererName'))
                        <div class="col-sm-10 col-sm-offset-2 error">
                            <span style="color: red" role="alert">
                                <strong>{{ $errors->first('CatererName') }}</strong>
                            </span>
                        </div>
                    @endif
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
                    <select name="EventNature[]" id="EventNature" class="form-control form-horizontal" multiple style="width: 100%" required>
                        {{-- <option value="">-- SELECT --</option> --}}
                        @foreach ($eventnatures as $eventnature)
                        <option value="{{ $eventnature->nature }}">{{ $eventnature->nature }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($errors->has('EventNature'))
                    <div class="col-sm-9 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EventNature') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{--  Number of Attendees  --}}
        <div class="row" style="padding-top: 1%">
            <div class="form-group">
                {{--  <label for="EventTitle" class="control-label col-sm-2">Number of Attendees:*</label>  --}}
                <p class="control-label col-sm-2">Number of Attendees:*</p>
                <div class="col-sm-10">
                    <input style="width: 20%" type="number" name="NumAttendees" id="NumAttendees" class="form-control form-horizontal" min="1" max="999" value="{{ old('NumAttendees') }}" required>
                </div>

                @if ($errors->has('NumAttendees'))
                    <div class="col-sm-10 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('NumAttendees') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{--  Time  --}}
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Start Time:*</p>
                    <div class="col-sm-2">
                        <input style="" type="time" name="TimeStart" id="timestart" class="form-control form-horizontal" value="{{ old('TimeStart') }}" required>
                    </div>

                    <p class="control-label col-sm-2">End Time:*</p>
                    <div class="col-sm-2">
                        <input style="" type="time" name="TimeEnd" id="timeend" class="form-control form-horizontal" value="{{ old('TimeEnd') }}" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-2 error">
                        @if ($errors->has('TimeStart'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 2%">{{ $errors->first('TimeStart') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('TimeEnd'))
                    <div class="col-sm-offset-1 col-sm-3 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('TimeEnd') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        {{--  Ingress/Eggress Time  --}}
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Ingress Time:*</p>
                    <div class="col-sm-2">
                        <input style="" type="time" name="IngressTime" id="" class="form-control form-horizontal" value="{{ old('IngressTime') }}" required>
                    </div>

                    <p class="control-label col-sm-2">Eggress Time:*</p>
                    <div class="col-sm-2">
                        <input style="" type="time" name="EggressTime" id="" class="form-control form-horizontal" value="{{ old('EggressTime') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3 col-sm-offset-2 error">
                        @if ($errors->has('IngressTime'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 2%">{{ $errors->first('IngressTime') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('EggressTime'))
                    <div class="col-sm-offset-1 col-sm-3 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EggressTime') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        {{--  Ingress/Eggress Date  --}}
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Ingress Date:</p>
                    <div class="col-sm-2">
                        <input style="" type="date" name="IngressDate" id="" class="form-control form-horizontal" value="{{ old('IngressDate') }}">
                    </div>

                    <p class="control-label col-sm-2">Eggress Date:</p>
                    <div class="col-sm-2">
                        <input style="" type="date" name="EggressDate" id="" class="form-control form-horizontal" value="{{ old('EggressDate') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3 col-sm-offset-2 error">
                        @if ($errors->has('IngressDate'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 1%">{{ $errors->first('IngressDate') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('EggressDate'))
                    <div class="col-sm-offset-1 col-sm-3 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EggressDate') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        {{--  Set Up  --}}
        <div class="row">
            <div class="form-group">
                <label for="SetupType" class="control-label col-sm-2">Physical Set-up:*</label>
                <div class="col-sm-8">
                    <select name="EventSetup" id="EventSetup" class="form-control form-horizontal" style="width: 100%" required>
                        @foreach ($eventsetups as $eventsetup)
                        <option value="{{ $eventsetup->setup }}">{{ $eventsetup->setup }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if ($errors->has('EventSetup'))
                    <div class="col-sm-8 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EventSetup') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{--  Audio/Visual  --}}
        <div class="row">
            <div class="form-group">
                <label style="margin-left: 4%;" class="control-label">Audio/Visual & Other Requirements:* </label>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-8">
                    <select id="Equipment" class="form-control form-horizontal">
                        {{-- <option value="">-- SELECT --</option> --}}
                        @foreach ($equipments as $equipment)
                        <option value="{{ $equipment->code }}" data-id="{{$equipment->id}}">{{ $equipment->name }} | {{ $equipment->description }}</option>
                        @endforeach
                    </select>
                </div>
                    
                <button type="button" data-toggle="modal" class="btn btn-default" data-target="#modalViewRates"> <i class="fa fa-eye"></i> View Rates</button>
                <button type="button" id="addEquipment" class="btn btn-default col-sm-1"> <i class="fa fa-plus"></i> Add</button>

                @if ($errors->has('equipments'))
                    <div class="col-sm-8 col-sm-offset-1 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('equipments') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div id="listEquipment" class="form-group">

            </div>
        </div>

        {{-- Event Organizer --}}
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-2">Event Organizer:*</label>
                <div class="col-sm-10" >
                    <input style="width:93%;" type="text" name="EventOrganizer" id="" class="form-control form-horizontal" value="{{ old('EventOrganizer') }}" required>
                </div>

                @if ($errors->has('EventOrganizer'))
                    <div class="col-sm-10 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EventOrganizer') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Event Organizer Contact Number --}}
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-2">Contact No:*</label>
                <div class="col-sm-10" >
                    <input style="width:93%;" type="text" name="EventOrganizerContactNo" id="" class="form-control form-horizontal" value="{{ old('EventOrganizerContactNo') }}" required>
                </div>

                @if ($errors->has('EventOrganizerContactNo'))
                    <div class="col-sm-10 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EventOrganizerContactNo') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Event Organizer Email--}}
        <div class="row">
            <div class="form-group">
                <label class="control-label col-sm-2">Email:*</label>
                <div class="col-sm-10" >
                    <input style="width:93%;" type="text" name="EventOrganizerEmail" id="" class="form-control form-horizontal" value="{{ old('EventOrganizerEmail') }}" required>
                </div>

                @if ($errors->has('EventOrganizerEmail'))
                    <div class="col-sm-10 col-sm-offset-2 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('EventOrganizerEmail') }}</strong>
                        </span>
                    </div>
                @endif
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
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Primary Contact:*</p>
                    <div class="col-sm-4">
                        <input type="text" name="primcontactinfo[name]" id="" class="form-control form-horizontal" value="{{ old('primcontactinfo[name]') }}" required>
                    </div>

                    <p class="control-label col-sm-2">Secondary Contact:</p>
                    <div class="col-sm-3" >
                        <input type="text" name="seccontactinfo[name]" id="" class="form-control form-horizontal">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5 col-sm-offset-2 error">
                        @if ($errors->has('primcontactinfo.name'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 1%">{{ $errors->first('primcontactinfo.name') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('seccontactinfo.name'))
                    <div class="col-sm-offset-1 col-sm-4 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('seccontactinfo.name') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        {{--  PrimSec TelNo  --}}
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Telephone Number:</p>
                    <div class="col-sm-4">
                        <input type="text" name="primcontactinfo[telno]" id="" class="form-control form-horizontal" >
                    </div>

                    <p class="control-label col-sm-2">Telephone Number:</p>
                    <div class="col-sm-3">
                        <input type="text" name="seccontactinfo[telno]" id="" class="form-control form-horizontal">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5 col-sm-offset-2 error">
                        @if ($errors->has('primcontactinfo.telno'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 1%">{{ $errors->first('primcontactinfo.telno') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('seccontactinfo.telno'))
                    <div class="col-sm-offset-1 col-sm-4 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('seccontactinfo.telno') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        {{--  PrimSec MobNo  --}}
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Mobile Number:*</p>
                    <div class="col-sm-4">
                        <input type="text" name="primcontactinfo[mobno]" id="" class="form-control form-horizontal" required>
                    </div>

                    <p class="control-label col-sm-2">Mobile Number:</p>
                    <div class="col-sm-3">
                        <input type="text" name="seccontactinfo[mobno]" id="" class="form-control form-horizontal">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5 col-sm-offset-2 error">
                        @if ($errors->has('primcontactinfo.mobno'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 1%">{{ $errors->first('primcontactinfo.mobno') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('seccontactinfo.mobno'))
                    <div class="col-sm-offset-1 col-sm-4 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('seccontactinfo.mobno') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        {{--  PrimSec Email  --}}
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Email:*</p>
                    <div class="col-sm-4">
                        <input type="text" name="primcontactinfo[email]" id="" class="form-control form-horizontal" required>
                    </div>

                    <p class="control-label col-sm-2">Email:</p>
                    <div class="col-sm-3">
                        <input type="text" name="seccontactinfo[email]" id="" class="form-control form-horizontal">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5 col-sm-offset-2 error">
                        @if ($errors->has('primcontactinfo.email'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 1%">{{ $errors->first('primcontactinfo.email') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('seccontactinfo.email'))
                    <div class="col-sm-offset-1 col-sm-4 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('seccontactinfo.email') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        {{--  Home/Company Address  --}}
        <div class="row" style="padding-bottom: 0%">
            <div class="form-group">
                <div class="row">
                    <p class="control-label col-sm-2" style="margin-left: 1%">Home/Company Address:*</p>
                    <div class="col-sm-4">
                        <input type="text" name="primcontactinfo[address]" id="" class="form-control form-horizontal" required>
                    </div>
                    
                    <p class="control-label col-sm-2">Home/Company Address:</p>
                    <div class="col-sm-3">
                        <input type="text" name="seccontactinfo[address]" id="" class="form-control form-horizontal">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5 col-sm-offset-2 error">
                        @if ($errors->has('primcontactinfo.address'))
                        <span style="color: red" role="alert">
                            <strong style="margin-left: 1%">{{ $errors->first('primcontactinfo.address') }}</strong>
                        </span>
                        @endif
                    </div>
                @if ($errors->has('seccontactinfo.address'))
                    <div class="col-sm-offset-1 col-sm-4 error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('seccontactinfo.address') }}</strong>
                        </span>
                    </div>
                @endif
                </div>
            </div>
        </div>

        <hr>{{-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------}}
        {{--  Consent  --}}
        <div class="row" style="pading-top: 0px">
            <div class="col-sm-2">@captcha()</div>
            <div class="col-sm-offset-4">
                <label class="" ><input name="consent" type="checkbox" id="consent"> I/We fully understand the <a href="" data-toggle="modal" data-target="#modalGuidelines">Bayanihan Center Guidelines</a> and we'll abide by its rules and regulations.</label>
            </div>
        </div>
        
        {{-- Submit Button --}}
        <div class="row" style="padding-top: 2%">
            <div class="col-sm-offset-10">
                <button type="submit" id="submit-form" class="btn btn-primary hidden">Submit</button>
                <button type="button" id="btnsubmit" class="btn btn-primary" data-toggle="modal" data-target="#modalDisplay">Submit</button>
            </div>
        </div>
    </form>
</div>