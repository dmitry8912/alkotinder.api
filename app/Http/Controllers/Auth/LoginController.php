<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Events\GoogleAuthConfirmed;
use App\Providers\RouteServiceProvider;
use http\Env\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Domain\Implementations\UsersRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Domain\Implementations\TokenTmpStorage;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirects to provider authorization
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function redirectToProvider(string $deviceUid)
    {
        $url = Socialite::driver('google')->redirect()->getTargetUrl();
        parse_str(parse_url($url)['query'],$query);
        TokenTmpStorage::setState($deviceUid, $query['state']);
        return response([
            'url' => $url,
            'state' => $query['state']
        ], 200);
    }

    /**
     * Handles a callback from provider
     */
    public function handleProviderCallback(\Illuminate\Http\Request $request)
    {
        session()->put('state', $request->input('state'));
        $externalUser = Socialite::driver('google')->user();
        $localUser = UsersRepository::getByExtId($externalUser->getId());
        if($localUser == null) {
            $localUser = UsersRepository::create(
                $externalUser->getEmail(),
                $externalUser->getName(),
                'google',
                $externalUser->getId()
            );
        }
        $token = $localUser->createToken('alkotoken');
        //dispatch to websockets
        TokenTmpStorage::setToken($request->input('state'),$token->plainTextToken);
        event(new GoogleAuthConfirmed(TokenTmpStorage::getState($request->input('state'))->device_uid));
        //render view
        return view('socialite/auth',['name'=>$externalUser->getName()]);
    }
}
