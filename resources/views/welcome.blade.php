<!DOCTYPE html>
<html lang="en">
<head> 
    <title>Url Shortner</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href=" https://use.fontawesome.com/releases/v5.8.1/css/all.css " />
</head>
<body>
    <div id="app">
        <router-view :key="$route.fullPath"></router-view>        
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    
    <script>
        $('body').on('DOMSubtreeModified', '#app', function(){
            document.getElementById('footer-layout').style.marginTop = "0px";

            let contentHeight = document.getElementById('main-layout').clientHeight;
            let footerHeight  = document.getElementById('footer-layout').clientHeight;
            let browserHeight = window.innerHeight;

            if (browserHeight > (contentHeight + footerHeight)) {
                document.getElementById('footer-layout').style.marginTop = (browserHeight - (contentHeight+footerHeight)) + "px";
            }
        });
    </script>
</body>
</html>
