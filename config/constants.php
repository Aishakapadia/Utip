<?php

// IDs from roles table
return [
    'PAGINATE_PER_PAGE' => 15, // for production it should be 15 - 20

    'ROLE_ID_SUPER'                               => '1',
    'ROLE_ID_ADMIN'                               => '2',
    'ROLE_ID_SUPPLIER'                            => '3',
    'ROLE_ID_TRANSPORTER'                         => '4',
    'ROLE_ID_VIEWER'                              => '5',
    'ROLE_ID_SITE_TEAM'                           => '6',

    /**
     * Ticket Statuses
     *
     * To generate following data run this query.
     * SELECT CONCAT('"', REPLACE(UPPER(s.slug), '-', '_'), '"'), '=>', CONCAT(s.id, ','), CONCAT('//', s.visible) FROM statuses s
     */
//    "OPEN_BY_SUPPLIER"                            => 1,                 //Request Pending for Admin Approval
//    "CANCEL_BY_ADMIN"                             => 2,                 //Request Cancelled By Admin
//    "APPROVE_BY_ADMIN"                            => 3,                 //Transporter Submission Pending
//    "ACCEPT_BY_TRANSPORTER"                       => 4,                 //In Progress - Transporters
//    "CONFIRM_TRANSPORTER_BY_ADMIN"                => 5,                 //Transporter Selected
//    "CANCELLED_BY_SUPPLIER"                       => 6,                 //Transporter Selected
//    "UPDATED_BY_TRANSPORTER"                      => 7,                 //Transporter Selected
//    "VEHICLE_APPROVED_BY_SUPPLIER"                => 8,                 //Vehicle Approved by Supplier
//    "VEHICLE_ARRIVED_BY_TRANSPORTER"              => 9,                 //Vehicle arrived at start point
//    "DELIVERY_CHALLAN_UPDATED_BY_SUPPLIER"        => 10,                //Delivery Challan Updated
//    "VEHICLE_LOADED_BY_TRANSPORTER"               => 11,                //Vehicle Loaded
//    "VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM" => 12,                //Vehicle reached at destination will updated by Site Team
//    "VEHICLE_OFFLOADED_BY_SITE_TEAM"              => 13,                //Vehicle Offloaded
    "OPEN_BY_SUPPLIER"                            => 1,                 //Request Pending for Admin Approval
    "CANCEL_BY_ADMIN"                             => 2,                 //Request Cancelled By Admin
    "APPROVE_BY_ADMIN"                            => 3,                 //Transporter Submission Pending
    "ACCEPT_BY_TRANSPORTER"                       => 4,                 //In Progress - Transporters
    "CONFIRM_TRANSPORTER_BY_ADMIN"                => 5,                 //Transporter Selected
    "VEHICLE_ARRIVED_BY_SUPPLIER"                 => 6,                 //Vehicle arrived at start point
    "CANCELLED_BY_SUPPLIER"                       => 7,                 //Vehicle Cancelled By Supplier
    "UPDATED_BY_TRANSPORTER"                      => 8,                 //Transporter Selected
    "VEHICLE_APPROVED_BY_SUPPLIER"                => 9,                 //Vehicle Approved by Supplier
    "DELIVERY_CHALLAN_UPDATED_BY_SUPPLIER"        => 10,                //Delivery Challan Updated
    "VEHICLE_LOADED_BY_SUPPLIER"                  => 11,                //Vehicle Loaded
    "VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM" => 12,                //Vehicle reached at destination
    "VEHICLE_OFFLOADED_BY_SITE_TEAM"              => 13,                //Vehicle Offloaded
    "DELETE_TICKET"                               => 14,

    /*
    *KPI for Users
    */
    "REPORT_DURATION"=>3000,
    "SUPPLIER_TAT"  => "04:00:00",
    "SITE_USER_TAT" => "04:00:00",
    "ADMIN_TAT"     => "01:00:00",
    "TRANSPORTER_TAT"     => "24:00:00",
    "TRANSPORTER_PUNCTUALITY" => "24:00:00",
    "TRANSIT_TIME" => "48:00:00",

    /*
    *Safety Checklist
    */
    "FAILED_RESPONSE"=>"fail",
    "TYPE_INBOUND"=>"Inbound",
    "TYPE_OUTB_SEC"=>"Outbound-Secondary",
    "TYPE_OUTB_PRI"=>"Outbound-Primary",

    /**
     * Transporter Statuses
     *
     * To generate following data run this query.
     * SELECT CONCAT('"', REPLACE(UPPER(s.slug), '-', '_'), '"'), '=>', CONCAT(s.id, ','), CONCAT('//', s.title) FROM transporter_statuses s;
     */
    "WIP"                                         => 1,                 //WIP
    "BID_SUBMITTED"                               => 2,                 //Bid Submitted
    "ON_HOLD"                                     => 3,                 //On Hold
    //"BID_UPDATED"                                   => 2,                 //Bid Submitted
    "ACCEPTED_BY_ADMIN"                           => 4,                 //Accepted By Admin
    "ACCEPTED_BY_SUPPLIER"                        => 5,                 //Accepted By Supplier
    "ACCEPTED_BY_TRANSPORTER"                     => 6,                 //Accepted By Transporter
    "REJECTED_BY_ADMIN"                           => 7,                 //Rejected By Admin
    "REJECTED_BY_SUPPLIER"                        => 8,                 //Rejected By Supplier
    "REJECTED_BY_TRANSPORTER"                     => 9,                 //Rejected By Transporter

    /**
     * Transporters
     *
     * SELECT CONCAT('"', UPPER(REPLACE(REPLACE(t.title, ' ', '_'), '-', '_')), '"'), '=>', CONCAT(t.id, ','), CONCAT('//', t.description) FROM transporters t
     */
    "CONNECT_LOGISTICS"                           => 1,                 //Connect Logistics
    "OPEN_PORT"                                   => 2,                 //Open Port
    "JIMMY_GOODS"                                 => 3,                 //Jimmy Goods
    "AL_HAYDER"                                   => 4,                 //Al-Hayder
    "CHAUDHARY_GOODS"                             => 5,                 //Chaudhary Goods
    "SPC"                                         => 6,                 //SPC
    "WALEED_GOODS"                                => 7,                 //Waleed Goods
    "NLC"                                         => 8,                 //NLC
    "TSD"                                         => 9,                 //TSD
    "ABDULLAH_ENTERPRISES"                        => 10,                //Abdullah Enterprises
    "AGILITY"                                     => 11,                //Agility

    "SMS_API_old" => 'https://pk.eocean.us/APIManagement/API/RequestAPI?user=myutip&pwd=ACDzVZu6XmLK1ZUOFVEgZcE2s2RhVIsfHg%2bOtZy3ky6I%2bg05S7xkDCd9VXeCjgBYrA%3d%3d&sender=myutip&reciever=##number&msg-data=##message&response=string',

    "SMS_SESSION_STRING" => 'https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923466377660&password=Nl%3B7C%2Ab%26uBzoQ-eE',
    
    "SMS_API" => 'https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id=##session_id&to=##number&text=##message&mask=MYUTIP',

    "NOTIFY_KEYS" => array(
        "SAVE TICKET",
        "SUBMIT TICKET",
        "TICKET CANCELLED BY ADMIN",
        "TICKET APPROVED BY ADMIN",
        "ACCEPTED BY TRANSPORTER",
        "TRANSPORTER SELECTED BY ADMIN",
        "",// index 6 VEHICLE ARRIVED
        "CANCELLED BY SUPPLIER",
        "UPDATED BY TRANSPORTER",
        "VEHICLE APPROVED BY SUPPLIER",
        "DELIVERY CHALLAN UPDATED BY SUPPLIER",
        "VEHICLE LOADED BY SUPPLIER",
        "VEHICLE REACHED AT DESTINATION BY SITE TEAM",
        "VEHICLE OFFLOADED BY SITE TEAM",
        "TICKET DELETED",
        "BID SUBMITTED BY TRANSPORTER",
        "VEHICLE LEFT BY SUPPLIER"
    ),

    "EMAIL_SUBJECT" => [
        "SAVE TICKET" => "A ticket ticket_num from myutip.com",
        "SUBMIT TICKET" => "A ticket ticket_num from myutip.com",
        "TICKET DELETED" => "Deleted- A ticket ticket_num from myutip.com",
        "TICKET CANCELLED BY ADMIN" => "Cancelled- A ticket ticket_num from myutip.com",
        "ACCEPTED BY TRANSPORTER" => "Transporter Accepted ticket_num",
        "TRANSPORTER SELECTED BY ADMIN" => "Transporter Selected ticket_num",
        "CANCELLED BY SUPPLIER" => "Cancelled- A ticket ticket_num from myutip.com",
        "UPDATED BY TRANSPORTER" => "Updated-A ticket ticket_num from myutip.com",
        "VEHICLE APPROVED BY SUPPLIER" => "Transporter Approved- ticket_num",
        "VEHICLE LEFT BY SUPPLIER" => "Vehilce Onway- ticket_num",
        "DELIVERY CHALLAN UPDATED BY SUPPLIER" => "Delivery Challan - ticket_num",
        "VEHICLE LOADED BY SUPPLIER" => "Vehicle Dispatched - ticket_num ",
        "VEHICLE REACHED AT DESTINATION BY SITE TEAM" => "Vehicle Reached - ticket_num",
        "VEHICLE OFFLOADED BY SITE TEAM" => "Vehicle Offloaded - ticket_num",
        "BID SUBMITTED BY TRANSPORTER" => "Bid Sumitted - ticket ticket_num from myutip.com",
        "TICKET APPROVED BY ADMIN"=>"Approved - ticket ticket_num from myutip.com",
    ],
    "EMAIL_RESPONSE_SUPPLIER" => [
        "SAVE TICKET" => "Hi ##user,<br><br>Your ticket has been created as draft.",
        "SUBMIT TICKET" => "Hi ##user,<br><br>Your ticket has been created.",
        "TICKET DELETED" => "",
        "TICKET CANCELLED BY ADMIN" => "Hi ##user,<br><br>Your ticket has been cancelled by admin",
        "ACCEPTED BY TRANSPORTER" => "",
        "TRANSPORTER SELECTED BY ADMIN" => "Hi ##user,<br><br>#transporter has been selected by admin, which will reach #destination with #vehicle_type at #trans_time",
        "CANCELLED BY SUPPLIER" => "Hi ##user,<br><br>You have cancelled the transporter.",
        "UPDATED BY TRANSPORTER" => "Hi ##user,<br><br>Transporter has provided a new vehicle and a #vehicle_type from #destination which will reach at #trans_time",
        "VEHICLE APPROVED BY SUPPLIER" => "",
        "VEHICLE LEFT BY SUPPLIER" => "Hi ##user,<br><br>#vehicle_type has left the #destination at #time.",
        "DELIVERY CHALLAN UPDATED BY SUPPLIER" => "",
        "VEHICLE LOADED BY SUPPLIER" => "",
        "VEHICLE REACHED AT DESTINATION BY SITE TEAM" => "",
        "VEHICLE OFFLOADED BY SITE TEAM" => "",
        "BID SUBMITTED BY TRANSPORTER" => "",
        "TICKET APPROVED BY ADMIN"=>"Hi ##user,<br><br>Your ticket has been approved by admin.",
    ],
    "EMAIL_RESPONSE_ADMIN" => [
        "SAVE TICKET" => "",
        "SUBMIT TICKET" => "Hi ##user,<br><br>Following ticket has been created. #supplier requires #vehicle_type from #destination to #site",
        "TICKET DELETED" => "Hi ##user,<br><br>Following ticket has been deleted.",
        "TICKET CANCELLED BY ADMIN" => "Hi ##user,<br><br>Following ticket has been cancelled.",
        "ACCEPTED BY TRANSPORTER" => "Hi ##user,<br><br>#transporter has accepted the ticket and has a #vehicle_type from #destination to #site which will reach at #trans_time",
        "TRANSPORTER SELECTED BY ADMIN" => "",
        "CANCELLED BY SUPPLIER" => "Hi ##user,<br><br>#supplier has cancelled the vehicle on the basis of following:<br><br>#reasons<br><br>",
        "UPDATED BY TRANSPORTER" => "Hi ##user,<br><br>#transporter has provided a new vehicle and  a #vehicle_type from #destination which will reach at #trans_time",
        "VEHICLE APPROVED BY SUPPLIER" => "",
        "VEHICLE LEFT BY SUPPLIER" => "Hi ##user,<br><br>#vehicle_type has left the #destination at #time.",
        "DELIVERY CHALLAN UPDATED BY SUPPLIER" => "",
        "VEHICLE LOADED BY SUPPLIER" => "",
        "VEHICLE REACHED AT DESTINATION BY SITE TEAM" => "",
        "VEHICLE OFFLOADED BY SITE TEAM" => "Hi ##user,<br><br>#vehicle_type has been offloaded by the #site at #time.",
        "BID SUBMITTED BY TRANSPORTER" => "Hi ##user,<br><br>#transporter has submitted a bid for the following ticket:<br><br>Details: #vehicle_type, #time.",
        "TICKET APPROVED BY ADMIN"=>"",
    ],
    "EMAIL_RESPONSE_TRANSPORTER" => [
        "SAVE TICKET" => "",
        "SUBMIT TICKET" => "",
        "TICKET DELETED" => "",
        "TICKET CANCELLED BY ADMIN" => "",
        "ACCEPTED BY TRANSPORTER" => "",
        "TRANSPORTER SELECTED BY ADMIN" => "Hi ##user,<br><br>You are selected, your #vehicle_type will reach #destination at #trans_time",
        "CANCELLED BY SUPPLIER" => "Hi ##user,<br><br>#supplier has cancelled your vehicle on the basis of following:<br><br>#reasons<br><br>Please provide a new vehicle",
        "UPDATED BY TRANSPORTER" => "Hi ##user,<br><br>You have provided a new #vehicle_type which will reach #destination at #trans_time.",
        "VEHICLE APPROVED BY SUPPLIER" => "Hi ##user,<br><br>Your vehicle has been approved by supplier.",
        "VEHICLE LEFT BY SUPPLIER" => "Hi ##user,<br><br>Your #vehicle_type has left #destination at #time.",
        "DELIVERY CHALLAN UPDATED BY SUPPLIER" => "",
        "VEHICLE LOADED BY SUPPLIER" => "",
        "VEHICLE REACHED AT DESTINATION BY SITE TEAM" => "Hi ##user,<br><br>Your vehicle has reached #site at #time.",
        "VEHICLE OFFLOADED BY SITE TEAM" => "",
        "BID SUBMITTED BY TRANSPORTER" => "Hi ##user,<br><br>Your bid has been placed for the #vehicle_type.",
        "TICKET APPROVED BY ADMIN"=>"Hi ##user,<br><br>A ticket has been created. #supplier requires #vehicle_type from #destination to #site",
    ],
    "EMAIL_RESPONSE_SITE_TEAM" => [
        "SAVE TICKET" => "",
        "SUBMIT TICKET" => "",
        "TICKET DELETED" => "",
        "TICKET CANCELLED BY ADMIN" => "",
        "ACCEPTED BY TRANSPORTER" => "",
        "TRANSPORTER SELECTED BY ADMIN" => "",
        "CANCELLED BY SUPPLIER" => "",
        "UPDATED BY TRANSPORTER" => "",
        "VEHICLE APPROVED BY SUPPLIER" => "",
        "VEHICLE LEFT BY SUPPLIER" => "",
        "DELIVERY CHALLAN UPDATED BY SUPPLIER" => "",
        "VEHICLE LOADED BY SUPPLIER" => "Hi ##user,<br><br>Your #vehicle_type has been dispatched at #time.",
        "VEHICLE REACHED AT DESTINATION BY SITE TEAM" => "",
        "VEHICLE OFFLOADED BY SITE TEAM" => "",
        "BID SUBMITTED BY TRANSPORTER" => "",
        "TICKET APPROVED BY ADMIN"=>"",
        ]
];