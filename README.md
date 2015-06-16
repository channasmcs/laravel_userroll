## Laravel PHP Framework ADD USER_ROLL BY CHANNASMCS

hi guy i make simple user ahtunticate methode usring   MIDDLEWARE  & i add user roles to access provilages 

this is simple prototype i hope this will help to you 

TO IMPLEMENT THIS, WE NEED 2 TABLES:
1. users
2. roles

DATABASE MIGRATION FOR users TABLE:
php artisan make:migration create_users_table
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('role_id')->unsigned();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password', 60);
            $table->string('first_name');
            $table->string('last_name');
			$table->rememberToken();
			$table->timestamps();
     $table->softDeletes();


		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
?>

DATABASE MIGRATION FOR roles TABLE:
php artisan make:migration create_roles_table

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('roles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('role_title');
            $table->string('role_slug');


        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('roles');

    }

}
?>

These tables are required to build the ACL fields could be changed (add/remove) but to build the relationship between tables we need foreign keys and we can’t remove those fields such as role_id in users table and the pivot table is also necessary as it is.
Now, we need to create the middleware class to check the user permissions and we can create it using php artisan make:middleware MyauthMiddleware  from command line/terminal. This will create a skeleton of a middleware class in app/Http/Middleware directory as MyauthMiddleware.php and now we need to edit that class as given below:

<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
// use App\Models\Roles;

class MyauthMiddleware {
    protected $auth;
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;


    }
    public function handle($request, Closure $next)
    {

// check user guest or not
        if ($this->auth->guest())
            {
                return response('Unauthorized', 401);
            }
        else
            {
                // check user roll
                if ($request->user()->role_id == 1)
                    {


                        return 'hello you are ADMIN';
                    }
                    else
                    {
                        return 'hello you are DEMOr';
                    }
            }

        return $next($request);

    }

}
?>
Now, before we can use our Middleware in any route declaration, we need to add it it in the app/Http/Kernel.php file and by default, there are already other middlewares added in that file by Laravel in the $routeMiddleware array and it looks like this:

<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
		'Myauth' => 'App\Http\Middleware\MyauthMiddleware',
	];

}
?>

then add this __construct file instand Auth on your controller
public function __construct()
	{
  //	$this->middleware('guest');
		$this->middleware('Myauth');
	}

then login ussing 

http://demain_name/public/auth/login


thank you



