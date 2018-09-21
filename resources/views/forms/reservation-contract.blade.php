<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>{{$title }}</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">

    <style>
        .table>thead>tr>th{
            background: #202020;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h2 class="text-center"><strong>UNILAB Bayanihan Center</strong></h2>
            <h2 class="text-center"><strong>Reservation Contract</strong></h2><br>
        </header>

        <div class="content">
            {{-- Brief Discussion of Reservation Info --}}
            <div class="row" style="">
                <p><strong>Full name:</strong> {{ $customer->name }} &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; <strong>Date Generated: </strong>{{date('Y-m-d h:i:s A', time())}}</p> 
                <p><strong>Customer Type:</strong> {{ $customer->type }}</p>
                <p><strong>Event Title:</strong> {{ $reservation->eventtitle }}</p>
                <p><strong>Event Date:</strong> {{ date('F d, Y', strtotime($reservation->eventdate)) }} | {{ date('h:iA', strtotime($reservationinfo->timestart)) }} - {{ date('h:iA', strtotime($reservationinfo->timeend)) }}</p>
                <p><strong>Ingress/Eggress Time:</strong> {{ date('h:iA', strtotime($reservationinfo->timeingress)) }} / {{ date('h:iA', strtotime($reservationinfo->timeeggress)) }}</p>
            </div>

            
            
            {{-- Comments Section --}}
            <div class="row">
                <p><strong><h3>Comments/Remarks:</h3></strong></p>
                <div class="well">
                    {{$reservation->billingComment}}
                </div>
            </div>
        </div>

        <footer class="footer">
            <p class="text-center">8008 Pioneer Street, Kapitoly, Pasig City, Metro Manila, Philippines <br> (02) 858-1978 | (02) 858-1985</p>
        </footer>
    </div>
</body>
</html>
