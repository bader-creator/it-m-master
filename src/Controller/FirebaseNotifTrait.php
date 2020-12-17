<?php


namespace App\Controller;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * Trait FirebaseNotifTrait
 * @package App\Controller
 * @property Controller $this
 */

trait FirebaseNotifTrait
{
    private static $FIREBASE_KEY = "AAAA2MJ1B8I:APA91bHeM315KW6pRo79SvogOLWSXawxtjDjRwVF79ejT-3Jf7I5aJUREW32VHhGz50D8fxUfkrW9Ux7sjNdvP_Tg2tSnwLNvU1OzI-tSm-StnQRcgcYIS0t7gpLr1tId4kIRJOtI2o7";

    private static $fields_notifications = ['message', 'token', 'title'];

    private static $NOTIFICATION_FIREBASE = true;

    public static function getFieldsNotifications() {
        return self::$fields_notifications;
    }

    public function pushNotificationFireBase($data)
    {
        if (method_exists($this, 'hasParameter') && $this->hasParameter('notification_firebase')) {
            self::$NOTIFICATION_FIREBASE = $this->getParameter('notification_firebase');
        } elseif (property_exists($this, 'container') && method_exists($this->container, 'hasParameter') && $this->container->hasParameter('notification_firebase')) {
            self::$NOTIFICATION_FIREBASE = $this->container->getParameter('notification_firebase');
        } elseif (!empty($container) && $container->hasParameter('notification_firebase')) {
            self::$NOTIFICATION_FIREBASE = $container->getParameter('notification_firebase');
        }

        if (self::$NOTIFICATION_FIREBASE == false) {
            return ['error' => 'notification disabled'];
        }

        if (method_exists($this, 'hasParameter') && $this->hasParameter('firebase_token')) {
            self::$FIREBASE_KEY = $this->getParameter('firebase_token');
        } elseif (property_exists($this, 'container') && method_exists($this->container, 'hasParameter') && $this->container->hasParameter('firebase_token')) {
            self::$FIREBASE_KEY = $this->container->getParameter('firebase_token');
        } elseif (!empty($container) && $container->hasParameter('firebase_token')) {
            self::$FIREBASE_KEY = $container->getParameter('firebase_token');
        }

        if (!empty($data['token']) && !empty('title') && !empty($data['message'])) {
            $fields = array(
                'to' => $data['token'],
                'data' => [
                    'title' => $data['title'],
                    'message' => $data['message'],
                    // 'landing_page' => 'congeavalider'
                ],
                'notification' => [
                    'title' => $data['title'],
                    'body' => $data['message'],
                    // 'click_action' => 'FCM_PLUGIN_ACTIVITY'
                ]
            );

            // Set POST variables
            $url = 'https://fcm.googleapis.com/fcm/send';

            $headers = array(
                'Authorization: key=' . self::$FIREBASE_KEY,
                'Content-Type: application/json'
            );

            if(extension_loaded('curl')) {
                // Open connection
                $ch = curl_init();

                try {
                    // Set the url, number of POST vars, POST data
                    curl_setopt($ch, CURLOPT_URL, $url);

                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // Disabling SSL Certificate support temporarily
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

                    // Execute post
                    $result = curl_exec($ch);

                    if ($result === FALSE) {
                        $error = 'Curl failed: ' . curl_error($ch);
                    }

                    $result = json_decode($result, true);
                    if (!is_array($result)) {
                        $result = [];
                    }
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                } finally {
                    // Close connection
                    curl_close($ch);
                }

                return ['request' => $fields, 'response' => $result] + @compact('error');
            } else {
                return ['error' => "Activer l'extension curl de php"];
            }
        }
        return ['error' => 'a missing parameter among the following attributes ' . implode(", ", self::$fields_notifications)];
    }
}


