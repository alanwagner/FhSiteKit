<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace SilPattern\Model;

class Pattern
{
    public $id;
    public $name;
    public $content;
    public $description;

    public function exchangeArray($data)
    {
        $this->id          = (!empty($data['id'])) ? $data['id'] : null;
        $this->name        = (!empty($data['name'])) ? $data['name'] : null;
        $this->content     = (!empty($data['content'])) ? $data['content'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
    }
}
