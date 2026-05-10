<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" media="all" type="text/css">
      <link href="{{ asset('css/report.css') }}" rel="stylesheet" media="all" type="text/css"> -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
      <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
      <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <title>Document</title>

</head>
<body style=" padding: 0px 20px ;">
    <div>
       <?php 
       $mytime = Carbon\Carbon::now();
       
       ?>
    @if(count($particpants) >= 1)
               
                @foreach($particpants as $particpant)
                <div style="height:100vh">
        <div class="row">
            <div class="col-md-6" style="border:solid 1px #000">
                <div class="row">
                    <div class="col-md-6">
                        <img style="margin-top:-35px" width="200px" src="{{ asset('img/hornsnew.png') }}" alt="">
            <p style="text-align:center"><b>{{isset($particpant->tracking_number) ? $particpant->tracking_number : ''}}</b></p>
                    </div>
                    <div class="col-md-6">
                        From :
                        Horns England<br>
                        Kaluthara<br>
                        Sri Lanka
                        <br>
                        <br>
                        <br>
                        Hoensengland@gmail.com<br>
                        +94759669668<br>
                        +94759669669
                    </div>
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                        
                    </div>

                </div>

            </div>
            <div class="col-md-6" style="border:solid 1px #000">
    
                 <p style="text-align:right">  {{$mytime->toDateTimeString()}}</p>
                <p><b>Name : {{isset($particpant->name) ? $particpant->name : ''}}</b></p>
                <p><b>Address : {{isset($particpant->adress) ? $particpant->adress : ''}}</b></p>
                <p ><b>Number : {{isset($particpant->phone_number) ? $particpant->phone_number : ''}}</b></p>
                <p >District : {{isset($particpant->district) ? $particpant->district : ''}}</b></p>

            </div>
            
        </div>
<!-- row 2 -->
        <div class="row" style="margin-top:20px">
            
        <div style="border:solid 1px #000" class="col-md-2">NO</div>
        <div style="border:solid 1px #000" class="col-md-6">Item code</div>
        <div style="border:solid 1px #000" class="col-md-2">Quantity</div>
        <div style="border:solid 1px #000" class="col-md-2">Amount</div>
        <div style="border-bottom:solid 1px #000;border-left:solid 1px #000;border-right:solid 1px #000" class="col-md-2">1</div>
        <div style="border-bottom:solid 1px #000;border-right:solid 1px #000" class="col-md-6"><b>{{isset($particpant->item_code) ? $particpant->item_code : ''}}</b></div>
        <div style="border-bottom:solid 1px #000;border-right:solid 1px #000" class="col-md-2">1</div>
        <div style="border-bottom:solid 1px #000;border-right:solid 1px #000" class="col-md-2"><b>{{isset($particpant->price) ? $particpant->price : ''}}</b></div>
        <div class="col-md-2"></div>
        <div  class="col-md-6"></div>
        <div  class="col-md-2">Total</div>
        <div style="border:solid 1px #000" class="col-md-2">{{isset($particpant->price) ? $particpant->price : ''}}</div>    
    </div>
    <!-- row 3 -->
    <div class="row" style="border:solid 1px #000;margin-top:15px">
        <p style="padding:0px 10px;">Note : <b> {{isset($particpant->note) ? $particpant->note : ''}} </b></p>
    </div>
    <p style="margin-top:15px;margin-left:-15px">THANKS FOR CONTACTING HORNS ENGLAND , PLEASE FOLLOW US TO GET FUTURE UPDATES.</p>

        </div>
             
                @endforeach
                @endif
         
    </div>
    <script type="text/javascript">
         $(document).ready(function(){
             $(window).load(function () {
                 document.title = 'dd';
                 window.print();
             });
         });
      </script>  
</body>
</html>