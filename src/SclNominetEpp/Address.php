<?php

namespace SclNominetEpp;

/**
 * Address class for handling address information.
 */
class Address extends \SclContact\Address
{
    /**
     * Set address lines from array.
     *
     * @param array $lines The address lines.
     * @return void
     */
    public function setLines(array $lines): void
    {
        if (count($lines) === 3) {
            $this->setLine1($lines[0] . ', ' . $lines[1]);
            $this->setLine2($lines[2]);
        }

        $this->setLine1($lines[0]);

        if (isset($lines[1])) {
            $this->setLine2($lines[1]);
        }
    }

    /**
     * @todo swap all references of state/province to County
     *
     */

    /**
     * @todo swap all references of countryCode to country
     */
}
