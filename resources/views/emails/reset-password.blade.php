<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="js.js" type="text/Javascript">
    <title>Document</title>
</head>

<body style="max-width: 640px;  margin: auto; height: 100%; box-sizing: border-box;background-color: #FAFAFA;">

    <main style="margin-left: 20px; margin-right: 20px; width: auto; min-height: 640px; display: flex; flex-direction: column; background-color: #FFFFFF;">
        <div style=" height: 100px; border-bottom: 1px solid #ECE9FF; padding-left: 24px;">
            <img src="@if (isset($logos)){{ asset($logos->fatherland_logo) }}@endif" alt="" style="width: 128px; height: 43px; margin-top: 28px;">
        </div>

        <section style="height: 118px; margin-left: 24px; margin-right: 24px; margin-top: 32px;">

            <h2 style="color:black">Hello, {{ $user->name }}</h2>

            <hr style="border: 1px solid #1ABC943B;">

            <p
                style="color: var(--Neutrals-Grey-2, #384860); font-family: Gotham Pro; font-size: 16px; font-style: normal; font-weight: 400; line-height: 150%; letter-spacing: 0.2px;">
                Your Password Reset Code Is:</p>

            </section>

            <h1
                style="color: #1ABC94; font-family: Gotham Pro; font-size: 32px; font-style: normal; font-weight: 700; line-height: 150%; letter-spacing: 0.2px; margin-top: 33px; text-align: center;">
                {{ $send_token }}
            </h1>

        <hr style="border: 1px solid #1ABC943B; margin-left: 24px; margin-top: 80px;">


        <section style="margin-top: auto;">

            <p
                style=" margin-left: 24px; color: var(--Neutrals-Grey-2, #384860); font-family: Gotham Pro; font-size: 12px; font-style: normal; font-weight: 400; line-height: 150%; letter-spacing: 0.2px;">
                {{ config('app.name') }} &copy;{{ date('Y') }} | All rights reserved
            </p>



            <div
                style="background-color: #1ABC94; display: flex; justify-content: space-between; width: auto; color: white;">
                <div style="padding-left:35px ;">
                    <img src="@if (isset($logos)){{ asset($logos->fatherland_logo) }}@endif" alt="" style="width: 80px; height: 38px;">
                    <div style="color: white;">
                        <p>Contact Support</p>
                        <p>Privacy</p>
                        <p>Terms</p>
                    </div>
                </div>

                <div style="text-align: right; padding-right: 35px; margin-left: auto;">
                    <p>
                        <a href="@if (isset($logos)){{ $logos->linkedin_url}}@endif"><img src="@if (isset($logos)){{ asset($logos->linkedin_logo) }}@endif" alt="linkedin" style="max-height: 35px; max-width: 33px;" /></a>
                        <a href="@if (isset($logos)){{ $logos->facebook_url}}@endif"><img src="@if (isset($logos)){{ asset($logos->facebook_logo) }}@endif" alt="facebook" style="max-height: 35px; max-width: 33px;" /></a>
                        <a href="@if (isset($logos)){{ $logos->instagram_url}}@endif"><img src="@if (isset($logos)){{ asset($logos->instagram_logo) }}@endif" alt="instagram" style="max-height: 35px; max-width: 33px;" /></a></p>
                    <p>Fatherland Global <br>
                         Avenue of the Americas, <br>
                        New York, United States
                    </p>
                </div>
            </div>

        </section>

    </main>
</body>

</html>
