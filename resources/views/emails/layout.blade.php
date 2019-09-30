<html>
  <head>
    <style>
        .container {          
          padding: 90px 145px;
        }
    
        .header,
        .main,
        .footer {
          color: black;
        }
    
        .header {          
          padding: 25px 25px;
          background-color: #FAFAFA;  
        }
    
        .header img {
          width: 65px;
          height: 65px;
        }
    
        .main {
          min-height: 250px;
          padding: 5px 25px;
          background-color: #FAFAFA;      
        }
    
        .footer {
          padding: 25px;
          background-color: #EEEEEE;      
        }

        @media only screen and (max-width: 768px){
          .container {
            padding: 10px 16px;
          }
        }
    </style>  
  </head>  
  <body>
    {{-- container --}}
    <div class="container">

      {{-- header --}}
      <div style="display: flex; align-items: center; padding: 25px 25px; background-color: #FAFAFA;">
        <img style="width: 65px; height: 65px;" src="https://app.ticsol.com.au/img/logo.svg" alt="ticsol logo">
      </div>

      {{-- main --}}
      <div style="min-height: 250px; padding: 5px 25px; background-color: #FAFAFA;">
        @yield('main')
      </div>

      {{-- footer --}}
      <div style="padding: 25px; background-color: #EEEEEE;">
        <div>@ 2019 <a href="https://ticsol.com.au">ticsol.com.au</a></div>
        <div>Company address here</div>
      </div>
    </div>
  </body>  
  </html>