<?php
namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Libraries\Repositories\UserRepository;
use App\User;
use App\Helpers\AccessToken;
use Illuminate\Http\Request;
use Response;
use Schema;
use DB;

class LoginAPIController extends AppBaseController
{
    
    /** @var  userRepository */
    private $userRepository;
    
    function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }
    
    public function login(Request $request) {
        $input = $request->all();
        
        $return = \Authorizer::issueAccessToken();
        
        $AccessToken = new AccessToken;
        $user = $AccessToken->getData($return['access_token']);
        if ($user) {
            $me = $this->userRepository->getMe($user->id);
            $return['features'] = $me['features'];
        }
        
        return Response::json($return);
    }
}
