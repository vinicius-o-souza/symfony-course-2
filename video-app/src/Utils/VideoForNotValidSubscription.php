<?php

namespace App\Utils;

use App\Entity\Video;
use Symfony\Component\Security\Core\Security;

class VideoForNotValidSubscription
{
    public $isSubscriptionValid = false;

    public function __construct(Security $security)
    {
        $user = $security->getUser();
        if ($user && $user->getSubscription != null) {
            $payment_status = $user->getSubscription()->getPaymentStatus();
            $valid = new \DateTime() < $user->getSubcription()->getValidTo();

            if ($payment_status != null && $valid) {
                $this->isSubscriptionValid = true;
            }
        }
    }

    public function check()
    {
        if ($isSubscriptionValid) {
            return null;
        } else {
            static $video = Video::videoForNotLoggedInOrNoMembers;
            return $video;
        }
    }
}