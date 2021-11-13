<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Backpack\Settings\app\Models\Setting;
use Mail;

class SendReferencesToEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * email-адрес для рассылыки справочников
     *
     * @var string
     */
    protected $email;

    /**
     * Тема письма
     *
     * @var string
     */
    protected $subject;

    /**
     * Текст сообщения
     *
     * @var string
     */
    protected $bodyText;


    /**
     * Массив, содержащий пути к файлам выгруженных справочников
     * (относительно структуры приложения)
     *
     * @var array
     */
    protected $referencesArray;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $subject, string $bodyText, array $referencesArray)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->bodyText = $bodyText;
        $this->referencesArray = $referencesArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->getSmtpParams();
        Mail::raw($this->bodyText, function($message)
        {
            $message->from(Setting::get('SMTP_USERNAME'));
            $message->to($this->email)->subject($this->subject);
            foreach($this->referencesArray as $attach) {
                $message->attach($attach);
            }
        });
    }

    /**
     * Устанавливает настройки SMTP-подключений на основании значений,
     * указанных в настройках админ-панели
     *
     * @return array
     */
    protected function getSmtpParams()
    {
        $currentMailer = config('mail');
        $mailer =array_merge(
            $currentMailer,
            [
                'mailers' => [
                    'smtp' => [
                        'transport' => 'smtp',
                        'host' => Setting::get('SMTP_HOST'),
                        'port' => Setting::get('SMTP_PORT'),
                        'encryption' => Setting::get('SMTP_ENCRYPTION'),
                        'username' => Setting::get('SMTP_USERNAME'),
                        'password' => Setting::get('SMTP_PASSWORD'),
                        'timeout' => null,
                        'auth_mode' => null,
                    ],
                    'from' => [
                        'address' => Setting::get('SMTP_USERNAME'),
                        'name' => Setting::get('SMTP_USERNAME'),
                    ],
                ],
            ]
        );

        return config(['mail'=>$mailer]);
    }
}
