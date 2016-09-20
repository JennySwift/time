<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Time</title>
    @include('shared.head-links')
</head>
<body>

<navbar></navbar>
<feedback></feedback>
<loading></loading>


<div class="container">
    <router-view></router-view>
</div>

@include('shared.footer.footer')
@include('shared.scripts')

</body>
</html>
