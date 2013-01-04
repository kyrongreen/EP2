<?php 

class Auth_User_Controller extends Base_Controller{
	
	public static $rules = array(
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed'
    );

	public function action_signup(){

		$validation = Validator::make( Input::all(), static::$rules );

		if ($validation->fails()){
			return Redirect::to('signup')->with_errors($validation->errors);
		}

		$user_data = array( 'email' => Input::get('email'), 'password' => Hash::make(Input::get('password')) ); 

	    $user = new User($user_data);
	    $user->save();

	    $redirect_url = Config::get('auth::config.bundle_route') . '/' . Config::get('auth::config.login_route');

	    return Redirect::to($redirect_url)->with('notification', 'Your Account has been Successfully Created! Please Login Below.');

	}

	public function action_login(){
		
	    // get login POST data
	    $userdata = array(
	        'username'      => Input::get('email'),
	        'password'      => Input::get('password')
	    );

	    $redirect_url = Config::get('auth::config.bundle_route');

	    if ( Auth::attempt($userdata) ){
	        // we are now logged in, go to dashboard
	    	$redirect_url .= '/' . Config::get('auth::config.dashboard_route');;
	        return Redirect::to($redirect_url);
	    } else {
	        // auth failure! redirect to login with errors
	        $redirect_url .= '/' . Config::get('auth::config.login_route');
	        return Redirect::to($redirect_url)->with('login_errors', true);
	    }

	}

}