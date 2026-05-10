<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- Bootstrap 4 --}}
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <title>Horns England – Invoice</title>

  <style>
    .invoice {
      border: 1px solid #000;
      padding: 16px;
      page-break-inside: avoid;
      background: #fff;
    }
    .brand-block {
      border: 1px solid #000;
      padding: 16px;
      height: 100%;
    }
    .to-block {
      border: 1px solid #000;
      padding: 16px;
      height: 100%;
    }
    .items-table th, .items-table td {
      border: 1px solid #000 !important;
    }
    .thankyou {
      margin-top: 12px;
      font-size: 0.95rem;
    }
    @media print {
      body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .page-break {
        page-break-after: always;
      }
      a[href]:after { content: ""; } /* cleaner prints */
    }
  </style>
</head>

<body class="bg-light" style="padding: 20px;">
  <div class="container">

    @php($now = now())

    @if(!empty($particpants) && count($particpants) >= 1)
      @foreach($particpants as $particpant)
        <div class="invoice mb-4">
          <div class="row">
            {{-- Left: From / Logo --}}
            <div class="col-md-6 mb-3 mb-md-0">
              <div class="brand-block">
                <div class="row">
                  <div class="col-6 text-center">
                    <img src="{{ asset('img/hornsnew.png') }}" alt="Horns England" style="max-width: 200px; margin-top: -8px;">
                    <p class="mt-2 mb-0"><b>{{ $particpant->tracking_number ?? '' }}</b></p>
                  </div>
                  <div class="col-6">
                    <div><b>From :</b></div>
                    Horns England<br>
                    Kalutara<br>
                    Sri Lanka<br><br>
                    <a href="mailto:hornsengland@gmail.com">Hornsengland@gmail.com</a><br>
                    +94 75 966 9668<br>
                    +94 75 966 9669
                  </div>
                </div>
              </div>
            </div>

            {{-- Right: To / Meta --}}
            <div class="col-md-6">
              <div class="to-block">
                <p class="text-right mb-1">{{ $now->format('Y-m-d H:i:s') }}</p>

                <p class="mb-1"><b>Name :</b> {{ $particpant->name ?? '' }}</p>
                <p class="mb-1"><b>Address :</b> {{ $particpant->address ?? '' }}</p>
                <p class="mb-1"><b>Number :</b> {{ $particpant->phone_number ?? '' }}</p>
                <p class="mb-0"><b>District :</b> {{ $particpant->district ?? '' }}</p>
              </div>
            </div>
          </div>

          {{-- Items --}}
          <div class="row mt-3">
            <div class="col-12">
              <table class="table table-sm items-table mb-2">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 10%;">NO</th>
                    <th>Item code</th>
                    <th style="width: 15%;">Quantity</th>
                    <th style="width: 18%;">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td><b>{{ $particpant->item_code ?? '' }}</b></td>
                    <td>1</td>
                    <td><b>{{ $particpant->price ?? '' }}</b></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <th>{{ $particpant->price ?? '' }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          {{-- Note --}}
          <div class="row">
            <div class="col-12">
              <div class="p-2" style="border:1px solid #000;">
                <b>Note:</b> {{ $particpant->note ?? '' }}
              </div>
            </div>
          </div>

          <p class="thankyou">
            THANKS FOR CONTACTING HORNS ENGLAND, PLEASE FOLLOW US TO GET FUTURE UPDATES.
          </p>
        </div>

        {{-- Force a new printed page per participant --}}
        <div class="page-break"></div>
      @endforeach
    @endif

  </div>

  <script type="text/javascript">
    // Print only after everything is loaded (including images)
    $(window).on('load', function () {
      document.title = 'Invoice - Horns England';
      window.print();
    });
  </script>
</body>
</html>
