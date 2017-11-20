<?php

namespace Aegir\Provision\Common;

use Aegir\Provision\Provision;

trait ProvisionAwareTrait
{
    /**
     * @var Provision
     */
    protected $provision = NULL;
    
    /**
     * @param Provision $provision
     *
     * @return $this
     */
    public function setProvision(Provision $provision = NULL)
    {
        $this->provision = $provision;
        
        return $this;
    }
    
    /**
     * @return Provision
     */
    public function getProvision()
    {
        return $this->provision;
    }
}
