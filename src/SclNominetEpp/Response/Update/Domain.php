<?php

namespace SclNominetEpp\Response\Update;

use SclNominetEpp\Domain as DomainObject;

class Domain extends AbstractUpdate
{
    public function getDomain(): ?DomainObject
    {
        return null;
    }
}
