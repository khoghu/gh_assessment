<!DOCTYPE html>
<html>
<head>
    <title>Parking Slot Management</title>
    <style>
    #carpark {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #carpark td, #carpark th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #carpark tr:nth-child(even){background-color: #f2f2f2;}

    #carpark tr:hover {background-color: #ddd;}

    #carpark th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Parking Slot Management</h1>
    <form action="{{ route('park-car') }}" method="POST">
        @csrf
        <label for="car_id" style="font-weight:bold;">Select a Car:</label>
        <br/>
        <select name="car_id" id="car" style="margin-top:5px;" required>
            <option value="" disable selected>Select a car</option>
            @foreach($cars as $car)
                <option value="{{ $car->id }}" car_id="{{ $car->id }}" @if($car->latestParking) disabled @endif>{{ $car->car_no }} @if($car->latestParking) (Occupied) @endif</option>
            @endforeach
        </select>
        <br/>
        <br/>
        <label for="parking_slot_id" style="font-weight:bold;">Select a Parking Slot:</label>
        <br/>
        <select name="parking_slot_id" id="parking-slot" style="margin-top:5px;" required>
            <option value="" disable selected>Select a Parking slot</option>
            @foreach($parkingSlots as $slot)
                <option value="{{ $slot->id }}" parking_slot="{{ $slot->id }}" @if($slot->latestParking) disabled @endif>{{ $slot->slot }} @if($slot->latestParking) (Occupied) @endif</option>
            @endforeach
        </select>
        <br/>
        <br/>
        <button type="submit">Park a Car</button>
    </form>
    <br/>
    <br/>
    <br/>
    @if(count($carParkings) > 0)
    <table id="carpark">
        <tr>
            <th>Car No</th>
            <th>Parking Slot</th>
            <th>Parking Time</th>
        </tr>
        @foreach($carParkings as $carParking)
        <tr parkSlotId="{{ $carParking->id }}">
            <td>{{ $carParking->car->car_no }}</td>
            <td>{{ $carParking->parking_slot->slot }}</td>
            <td>{{ \Carbon\Carbon::create($carParking->updated_at)->addHour(8)->format('Y-m-d H:i:s') }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <script>
        $(document).ready(function() {
            // Set up a setInterval to execute a function every 30000 milliseconds (30 second)
            var intervalId = setInterval(function() {
                $.ajax({
                    url: "http://127.0.0.1:8000/get-unlock-parking",
                    type: 'GET',
                    dataType: "JSON",
                    success: function(data) {
                        if(data.success){
                            data.park_data.forEach(function(car_park) {
                                var car_text = $('option[car_id="'+ car_park.car_id +'"]').text().replace(" (Occupied)", "");
                                $('option[car_id="'+ car_park.car_id +'"]').text(car_text)
                                $('option[car_id="'+ car_park.car_id +'"]').prop("disabled", false);
                                var parking_slot_text = $('option[parking_slot="'+ car_park.parking_slot_id +'"]').text().replace(" (Occupied)", "");
                                $('option[parking_slot="'+ car_park.parking_slot_id +'"]').text(parking_slot_text)
                                $('option[parking_slot="'+ car_park.parking_slot_id +'"]').prop("disabled", false);
                                $('tr[parkSlotId="'+ car_park.car_park_id +'"]').remove()
                            });           
                            if(data.carParkings.length == 0){
                                $("#carpark").remove()
                                clearInterval(intervalId)
                            } 
                        } else {
                            if(data.carParkings.length == 0){
                                clearInterval(intervalId)
                            }
                        }
                    },
                    error:function(error){
                        console.log("fail");
                        var error = eval("(" + error.responseText + ")");
                        console.log(error);
                    }
                });
            }, 5000);
            //5 * 60 * 1000
        });
    </script>
</body>
</html>
