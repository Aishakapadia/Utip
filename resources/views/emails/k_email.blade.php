@extends('emails.layout')

@section('content')
@php
if(array_key_exists('ticket',$data)){
    $vehicle = DB::select("select * from vehicle_types where id = ".$data['ticket']->vehicle_type_id)[0];
    $vehicle_size = $vehicle->title;
    $vehicle_spec = $vehicle->sap_code;
    $transporter = $data['ticket']->relationTransporters->first();
    $transporter_name = $transporter->title;
    $transporter_code = $transporter->sap_code;
    $vehicle_number = $transporter->pivot->vehicle_number;
    $lane = DB::select("select * from lanes where site_id_from = ".$data['ticket']->site_id_from.
    " and site_id_to = ".$data['ticket']->site_id_to)[0];
    $route = $lane->title;
    $route_code = $lane->sap_code;
    $plant = $lane->plant_code;

    $date = DB::select("SELECT st.created_at from status_ticket as st
    join (select max(id) as id from status_ticket group by ticket_id) as t
    on t.id = st.id
    where ticket_id = ".$data['ticket']->id." and st.status_id =
    ".$data['ticket']->relationActiveStatus()->id)[0]->created_at;

    $details = $data['ticket']->details;
    $units = DB::select("select id,title from units");
    $temp = [];
    foreach ($units as $value) {
    $temp[$value->id] = $value;
    }
    $units = $temp;

    $materials = DB::select("select id,title,sap_code from materials");
    $temp = [];
    foreach ($materials as $value) {
    $temp[$value->id] = $value;
    }
    $materials = $temp;
}
@endphp

@if(array_key_exists('ticket',$data))
<p style="font-size:14px;">{!! $data['message'] !!}</p>
<table width="600" cellpadding="5" cellspacing="0" border="1" class="container">
    {{-- Offloaded Time --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Offloaded Date</td>
        <td width="300" class="mobile" align="center" valign="top">{{$date}}</td>
    </tr>
    {{-- Plant Code --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Plant Code</td>
        <td width="300" class="mobile" align="center" valign="top">{{$plant}}</td>
    </tr>
    {{-- Delivery Challan --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Delivery Challan</td>
        <td width="300" class="mobile" align="center" valign="top">
            {{ $data['ticket']->delivery_challan_number }}</td>
    </tr>
    {{-- Transporter Name --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Transporter Name</td>
        <td width="300" class="mobile" align="center" valign="top">{{$transporter_name}}</td>
    </tr>
    {{-- Transporter Code --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Transporter Code</td>
        <td width="300" class="mobile" align="center" valign="top">{{$transporter_code}}</td>
    </tr>
    {{-- Vehicle Number --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Vehicle Number</td>
        <td width="300" class="mobile" align="center" valign="top">{{$vehicle_number}}</td>
    </tr>
    {{-- Vehicle Specification --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Vehicle Specification</td>
        <td width="300" class="mobile" align="center" valign="top">{{$vehicle_spec}}</td>
    </tr>
    {{-- Vehicle Size --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Vehicle Size</td>
        <td width="300" class="mobile" align="center" valign="top">{{$vehicle_size}}</td>
    </tr>
    {{-- Route Description --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Route Description</td>
        <td width="300" class="mobile" align="center" valign="top">{{$route}}</td>
    </tr>
    {{-- Route Code --}}
    <tr>
        <td width="300" class="mobile" align="center" valign="top">Route Code</td>
        <td width="300" class="mobile" align="center" valign="top">{{$route_code}}</td>
    </tr>
</table>
<br><br>
@php
$n=1;
@endphp
<table width="100%" cellpadding="5" cellspacing="0" border="1">
    <thead>
        <tr>
            <th align="center" valign="top">SNo.</th>
            <th align="center" valign="top">Item Code</th>
            <th align="center" valign="top">Description</th>
            <th align="center" valign="top">UoM</th>
            <th align="center" valign="top">Quantity</th>
            <th align="center" valign="top">Weight</th>
            <th align="center" valign="top">IBD Number</th>
            <th align="center" valign="top">PO Number</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $item)
        <tr>
            <td align="center" valign="top">{{$n++}}</td>
            <td align="center" valign="top">{{$materials[$item->material_id]->sap_code}}</td>
            <td align="center" valign="top">{{$materials[$item->material_id]->title}}</td>
            <td align="center" valign="top">{{$units[$item->unit_id]->title}}</td>
            <td align="center" valign="top">{{$item->quantity}}</td>
            <td align="center" valign="top">{{$item->weight}}</td>
            <td align="center" valign="top">{{$item->ibd_number}}</td>
            <td align="center" valign="top">{{$item->po_number}}</td>
        </tr>
        @endforeach
    </tbody>
</table>    
@else
    <p style="font-size:14px;">{!! $data['message'] !!}</p>  
@endif

@if (array_key_exists('summary',$data))
<h5>Ticket Summary</h5>
<img src={!! $data['summary'] !!} alt="" style="width:100%;" />
@endif

@stop