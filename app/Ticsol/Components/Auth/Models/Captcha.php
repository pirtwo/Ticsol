<?php

namespace App\Ticsol\Components\Models;

use Illuminate\Http\Request;

class Captcha implements ICaptcha
{
    protected $_errors;

    public function __construct()
    {
    }

    /**
     * Verify user reCAPTCHA response.
     *
     * @param \Illuminate\Http\Request $req
     * @return bool
     */
    public function Verify(Request $request)
    {
        try {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => env('reCAPTCHA_SicretKey'),
                'response' => $request['g-recaptcha-response'],
                'remoteip' => $request->ip());
            
            $handel = curl_init($url);
            curl_setopt($handel, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handel, CURLOPT_POST, true);
            curl_setopt($handel, CURLOPT_POSTFIELDS, $data);
    
            $respond = json_decode(curl_exec($handel));
            curl_close($handel);

            if ($respond->success === true) {
                return true;
            } else {
                $this->_errors = $respond->{'error-codes'};
                return false;
            }
        } catch (Exeption $e) {
            $this->_errors = $e->getMessage();
            return false;
        }
    }

    /**
     * Return current error. 
     * @return string
     */
    public function GetErrors()
    {
        $error;
        foreach ($this->_errors as $e) {
            switch ($e) {
                case 'missing-input-secret':
                    $error['reCaptcha'] = 'CAPTCHA missing input secret';
                break;
                case 'invalid-input-secret':
                    $error['reCaptcha'] = 'CAPTCHA invalid input secret';
                break;
                case 'missing-input-response':
                    $error['reCaptcha'] = 'CAPTCHA missing input response';
                break;
                case 'invalid-input-response':
                    $error['reCaptcha'] = 'CAPTCHA invalid input response';
                break;
                case 'bad-request':
                    $error['reCaptcha'] = 'CAPTCHA bad request';
                break;
                default:
                    $error['reCaptcha'] = 'CAPTCHA unknown Error';
                break;
            }
            break;
        }
        return $error;
    }

    /**
     * Return reCAPTCHA widget.
     *
     * @param string $theme
     * @param string $callback
     * @return string
     */
    public static function Widget($theme, $callback = null)
    {
        $sitekey = env('reCAPTCHA_SiteKey');

        echo <<<_widget_
        <div class='g-recaptcha' id='g-recaptcha'></div>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
        </script>
        <script type='text/javascript'>        
        var onloadCallback = function() {          
            grecaptcha.render('g-recaptcha', {
                'sitekey' : '{$sitekey}',
                'theme' : '{$theme}',
                'callback': '{$callback}'
            });
        };
      </script>
_widget_;
    }
}
