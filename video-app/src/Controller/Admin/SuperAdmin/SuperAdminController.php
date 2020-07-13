<?php

namespace App\Controller\Admin\SuperAdmin;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class SuperAdminController
{

    /**
     * @Route("/su/upload-video", name="upload_video")
     */
    public function uploadVideo()
    {
        return $this->render('admin/upload_video.html.twig');
    }
    
    /**
     * @Route("/su/users", name="users")
     */
    public function users()
    {
        return $this->render('admin/users.html.twig');
    }
}