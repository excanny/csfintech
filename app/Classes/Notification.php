<?php
/**
 * Created by Canaan Etai.
 * Date: 9/8/19
 * Time: 2:18 PM
 */
namespace App\Classes;

use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class Notification
{
    public static function sendToTopic($user, $topic, $title, $message, $data)
    {
        $nb = new PayloadNotificationBuilder($title);
        $nb->setBody($message)->setSound('default');
        $notification = $nb->build();
        $fcmTopic = new Topics();
        $fcmTopic->topic($topic);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);
        $dataB = $dataBuilder->build();

        FCM::sendToTopic($fcmTopic, null, $notification, $dataB);

        // save notification.
        $user->notifications()->create([
            'title'     => $title,
            'body'      => $message,
            'type'      => $data['type'],
            'payload'   => $data
        ]);
    }

    public static function updateAvailable($title, $message, $data)
    {
        $nb = new PayloadNotificationBuilder($title);
        $nb->setBody($message)->setSound('default');
        $nb->setImage('http://192.168.0.2:8000/assets/images/advert.jpg');
        $notification = $nb->build();
        $fcmTopic = new Topics();
        $fcmTopic->topic('app-update');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);
        $dataB = $dataBuilder->build();

        FCM::sendToTopic($fcmTopic, null, $notification, $dataB);
    }


    public static function sendToDevice($user, $title, $message, $payload, $isChat = false)
    {
        try {
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*5);
            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($message)->setSound('default');
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData($payload);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $downstreamResponse = FCM::sendTo($user->device_id, $option, $notification, $data);

            // save notification.
            if ( !$isChat ) {
                $user->notifications()->create([
                    'title'     => $title,
                    'body'      => $message,
                    'type'      => $payload['type'],
                    'payload'   => $payload
                ]);
            }
        }
        catch (\Exception $exception ) {
//            dd($exception->getMessage());
        }
    }
}
