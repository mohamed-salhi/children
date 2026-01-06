<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASQ Est</title>
    <!-- css links files -->
    <link href="{{asset('ASQ Store/src/output.css')}}" rel="stylesheet">
    <link href="{{asset('dashboard/app-assets/images/logo/Logo.png')}}" rel="icon">
    <link href="{{asset('dashboard/app-assets/images/logo/Logo.png')}}"
    <link rel="stylesheet" href="{{asset('ASQ Store/src/assets/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('ASQ Store/src/assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('ASQ Store/src/assets/css/hover-min.css')}}">
    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

</head>

<body class="bg-[#F6FAF6]">
<!-- loader -->
<div class="loader fixed flex justify-center items-center z-50 bg-[#ffffff] top-0 bottom-0 left-0 right-0 ">
    <img src="{{asset('ASQ Store/src/assets/images/Rectangle 19574.png')}}" alt="Logo">
    <div class="spinner-5"></div>
</div>
<!-- signin page -->
<div class="container mx-auto flex justify-center items-start mt-20">
    <div class="signinCard w-full md:w-1/2">
        @include('web.errors')

        <h1 class="text-4xl font-medium">@lang enter your email @endlang</h1>
        <form id="edit-password-form" method="post"  action="{{ route('user.forgetPasswordPost') }}" >
            @csrf
            <div class="form-group my-8">
                <label for="" class="text-[#000100] text-base">@lang('Email Address')</label>
                <input style="box-shadow: 0px 0px 3px 0px #00000040;" type="text"
                       class="p-2 rounded-md bg-white mt-2 w-full transition-all duration-200 focus:outline-0 focus:scale-105 focus:border focus:border-[#095533]"
                       placeholder="Ahmed@gmail.com" name="email">
            </div>

            <div class="flex flex-col justify-center items-center my-10">
                <button type="submit" class="bg-[#095533] text-white py-2 px-6 rounded-full mb-2">@lang('send')</button>
            </div>
        </form>
    </div>
</div>
<script src="{{asset('ASQ Store/src/assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('ASQ Store/src/assets/js/script.js')}}"></script>
</body>

</html>
