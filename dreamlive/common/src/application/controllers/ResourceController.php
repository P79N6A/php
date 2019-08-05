<?php
class ResourceController extends BaseController
{
    public function getResourceAction()
    {
        $this->render(Resource::getResource());
    }
}
