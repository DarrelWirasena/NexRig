use Illuminate\Auth\Events\Login;
use App\Listeners\MoveCartToDatabase;

protected $listen = [
    Registered::class => [
        SendEmailVerificationNotification::class,
    ],
    // TAMBAHKAN INI:
    Login::class => [
        MoveCartToDatabase::class,
    ],
];