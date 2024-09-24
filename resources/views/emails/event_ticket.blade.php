<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="js.js" type="text/Javascript" />


        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
            rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap"
            rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap"
            rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap"
            rel="stylesheet" />
    <title>Document</title>
</head>
<body style="font-family: Poppins; display: flex; flex-direction: column; gap: 80px;">

    <style>
        @media (max-width: 640px) {
            #head_details {

                display: block !important;


            }

            footer {
                padding-top: 35px !important;
                padding-bottom: 35px !important;
                gap: 20px !important;
            }


            body {
                gap: 30px !important;

            }

            #reconet_ticket {
                padding-top: 30px !important;
                padding-bottom: 30px !important;
                padding-left: 30px !important;
                padding-right: 30px !important;
                gap: 15px !important;

            }

            #reconet_ticket > h1 {
                font-weight:550 !important;
                font-size:25px !important;
                line-height:34px !important;

            }

            #hello {
                font-weight:250 !important;
                font-size:18px !important;
                line-height:28px !important;

            }

            header {
                padding-top: 20px !important;
                padding-bottom: 20px !important;
            }
        }

        @media (max-width: 916px) {
            #reconet_ticket {
                margin-left: 15px !important;
                margin-right: 15px !important;
            }
        }
    </style>

<header style="background-color: #F0F3F5; padding-top: 60px; padding-bottom: 60px; text-align: center;">
    <img src="@if (isset($logos)){{ asset($logos->fatherland_logo) }}@endif" alt="fatherland" style="max-width: 166px; max-height: 40px;">
</header>
    <div id="hello" style="max-width: 868px;  font-weight:400; font-size:24px ; line-height:36px; margin: auto; text-align: center;">
        <p>Hello {{ $user->name}},</p>

    <p>You just purchased a ticket to attend <span style="color: #28D744;">{{$event->name}}.</span>
        See below for your ticket details.</p>
    </div>

    <div id="reconet_ticket" style="background: #E8FDFA; padding-left: 80px; padding-right: 80px; padding-top: 80px; padding-bottom: 80px; margin-left: 128px; margin-right: 128px;  display: flex; flex-direction: column; gap: 40px;">


            <h1 style="font-weight:700; font-size:32px ; line-height:48px;" >Ticket for <span style="color: #28D744">{{$event->name}}</span> </h1>
        
    
        <div style=" display: flex; flex-direction: column; gap: 20px;">
            <h1 style="font-weight:600; font-size:16px ; line-height:24px; color: #28D744;">Ticket Details</h1>

            <div id="head_details" style="display: flex; gap: 80px;">
            <div >
                <p style="font-weight:400; font-size:12px ; line-height:18px;">Ticket ID</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$ticketId}}</p>
            </div>

           <div>
            <p style="font-weight:400; font-size:12px ; line-height:18px;">Type</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$ticketType->name}}</p>
           </div>

            <div>
            <p style="font-weight:400; font-size:12px ; line-height:18px;">Admit</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$quantity}}</p>
            </div>

            <div>
                <p style="font-weight:400; font-size:12px ; line-height:18px;">price</p>
                <p style="font-weight:600; font-size:16px ; line-height:24px;">${{$ticketType->price}}</p>
            </div>

            <div>
                <p style="font-weight:400; font-size:12px ; line-height:18px;">total</p>
                <p style="font-weight:600; font-size:16px ; line-height:24px;" id="total_price">${{$total}}</p>
            </div>
        </div>
       </div>

        <div style="display: flex; flex-direction: column; gap: 24px;">
            <h1 style="font-weight:600; font-size:16px ; line-height:24px; color: #28D744;">Event Details</h1>

           <div id="head_details" style="display: flex; gap: 80px;">
            <div>
                <p style="font-weight:400; font-size:12px ; line-height:18px;">Event Name</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$event->name}}</p>
            </div>

           <div>
            <p style="font-weight:400; font-size:12px ; line-height:18px;">Venue</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$event->location}}.</p>
           </div>

            <div>
                <p style="font-weight:400; font-size:12px ; line-height:18px;">Date</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">Saturday, 27 April, 2024</p>
            </div>

            <div>
                <p style="font-weight:400; font-size:12px ; line-height:18px;">Time</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">10:00 AM</p>
            </div>
           </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 24px;">
            <h1 style="font-weight:600; font-size:16px ; line-height:24px; color: #28D744;">Contact Details</h1>
            <div id="head_details" style="display: flex; gap: 80px;">
                <div>
                    <p style="font-weight:400; font-size:12px ; line-height:18px;">Name</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$user->name}}</p>
                </div>

            <div>
                <p style="font-weight:400; font-size:12px ; line-height:18px;">Contact Details</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$user->email}}</p>
            </div>

           <div >
            <p style="font-weight:400; font-size:12px ; line-height:18px;">Phone Number</p>
            <p style="font-weight:600; font-size:16px ; line-height:24px;">{{$user->phone_number}}</p>
           </div>
            </div>

        </div>
    </div>

    <div style="max-width: 630px; font-weight:600; font-size:24px ; line-height:36px; text-align: center; margin: auto;">
            <p>Do enjoy the event</p>

            <p>Feel free to reach out to us if you need anything else.</p>

            <p>Cheers.</p>


    </div>

    <footer style="
          background: #14202d;
          color: white;
          text-align: center;
          display: flex;
          flex-direction: column;
          padding-top: 80px;
          padding-bottom: 80px;
          align-items: center;
          gap: 40px;
        ">

       <img src="maskgroup.png" alt="" style="width: 332px; height: 80px;">

       <div style=" display: flex; justify-content: space-between; gap: 80px;">

            <h4 style="
      font-weight: 400;
      font-size: 14px;
      line-height: 36px;
      height: 38.13px;
    ">
                Check out other events
            </h4>



            <h4 style="
      font-weight: 400;
      font-size: 14px;
      line-height: 36px;
      text-align: center;
    ">
                Customer Support
            </h4>

    </div>

                    <div style="

                display: flex;
                justify-content: space-between;
                gap: 24px;
              ">
                        <a href="@if (isset($logos)){{ $logos->linkedin_url}}@endif"><img src="@if (isset($logos)){{ asset($logos->linkedin_logo) }}@endif" alt="linkedin" style="max-height: 35px; max-width: 33px;" /></a>
                        <a href="@if (isset($logos)){{ $logos->facebook_url}}@endif"><img src="@if (isset($logos)){{ asset($logos->facebook_logo) }}@endif" alt="facebook" style="max-height: 35px; max-width: 33px;" /></a>
                        <a href="@if (isset($logos)){{ $logos->instagram_url}}@endif"><img src="@if (isset($logos)){{ asset($logos->instagram_logo) }}@endif" alt="instagram" style="max-height: 35px; max-width: 33px;" /></a>
                    </div>
                </nav>


            </div>
        </footer>
       
</body>
</html>
