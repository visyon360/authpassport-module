<?php

namespace Modules\AuthPassport\Notifications;

use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var Closure|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var Closure|null
     */
    public static $toMailCallback;

    private $name;


    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @param $name
     */
    public function __construct($token, $name)
    {
        $this->token = $token;

        $this->name = $name;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if (static::$createUrlCallback) {
            $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        } else {
            $url = url(route(
                'password.reset',
                [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ],
                false
            ));
        }

        return $this->buildMailMessage($url, $this->name);
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     *
     * @return MailMessage
     */
    protected function buildMailMessage($url, $name)
    {
        return (new MailMessage)
          ->greeting('Hello '.$name.'!')
          ->subject(Lang::get('Reset Password Notification'))
          ->line(Lang::get('You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:'))
          ->action(Lang::get('Reset Password'), $url)
          ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
          ->line(Lang::get('If you did not make this request, just ignore this email. Otherwise please click the button above to reset your password.'));
    }

    /**
     * Set a callback that should be used when creating the reset password button URL.
     *
     * @param  Closure  $callback
     *
     * @return void
     */
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  Closure  $callback
     *
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
