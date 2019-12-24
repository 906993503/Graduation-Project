<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="/images/favicon.ico">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/css/app.css">

</head>

<body class="base-body">
    <div id="app">
        <sui-dimmer :active="isLoad" :style="load_style">
            <sui-loader>加载中</sui-loader>
        </sui-dimmer>
        <router-view :user="user" :type="type" :msg-rows="msgRows"></router-view>
        <tips></tips>
    </div>
    <script type="text/javascript" src="/js/app.js"></script>
</body>

</html>