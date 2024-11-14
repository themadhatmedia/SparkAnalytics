<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->

    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="{{ $data['midtrans_secret'] }}"></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  </head>

  <body>


    <form action="{{ route($data['fallback_url'], $data) }}" id="submit_form" method="POST">
      @csrf
      <input type="hidden" name="json" id="json_callback">
  </form>
  
  <script type="text/javascript">
      // Trigger Snap popup
      window.snap.pay('{{$data['snap_token']}}', {
          onSuccess: function(result) {
              console.log(result);
              sendResponseToForm(result);
          },
          onPending: function(result) {
              console.log(result);
              sendResponseToForm(result);
          },
          onError: function(result) {
              console.log(result);
              sendResponseToForm(result);
          },
          onClose: function() {
              alert('You closed the popup without finishing the payment');
              window.location.href = "{{ route('plans') }}";
          }
      });
  
      function sendResponseToForm(result) {
          document.getElementById('json_callback').value = JSON.stringify(result);
          document.getElementById('submit_form').submit();
      }
  </script>
  </body>
</html>
