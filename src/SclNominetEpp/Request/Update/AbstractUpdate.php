<?php

namespace SclNominetEpp\Request\Update;

use SclNominetEpp\Request;
use SclNominetEpp\Response;
use SclNominetEpp\Request\Update\Field\UpdateFieldInterface;

/**
 * This abstract class enables building the XML for a Nominet EPP update command.
 */
abstract class AbstractUpdate extends Request
{
    /**
     * The type of update operation.
     *
     * @var string
     */
    protected string $type;

    /**
     * The namespace for the update operation.
     *
     * @var string
     */
    protected string $updateNamespace;

    /**
     * The name of the value being updated.
     *
     * @var string
     */
    protected string $valueName;

    /**
     * The value being updated.
     *
     * @var string
     */
    protected string $value;

    /**
     * Constructor for AbstractUpdate.
     *
     * @param string        $type            The type of update operation.
     * @param Response|null $response        The response object.
     * @param string        $updateNamespace The namespace for the update operation.
     * @param string        $valueName       The name of the value being updated.
     * @return void
     */
    public function __construct(string $type, ?Response $response, string $updateNamespace, string $valueName)
    {
        parent::__construct('update', $response);

        $this->type            = $type;
        $this->updateNamespace = $updateNamespace;
        $this->valueName       = $valueName;
    }

    /**
     * Set the value to lookup/update.
     *
     * @param string $value The value to set.
     * @return static
     */
    public function lookup(string $value): AbstractUpdate
    {
        $this->value = $value;

        return $this;
    }

        /**
     * The <b>add()</b> function assigns a Field object as an element of the add array
     * for including specific fields in the update request "{$this->type}:add" tag.
     * ($this->type = 'domain' || 'contact' || 'contactID' || 'host'; (pseudo-code))
     *
     * @param UpdateFieldInterface $field The field to add.
     * @return void
     */
    protected function add(UpdateFieldInterface $field)
    {
        $this->add[] = $field;
    }

    /**
     * The <b>remove()</b> function assigns a Field object as an element of the remove array
     * for including specific fields in the update request "{$this->type}:remove" tag.
     * ($this->type = 'domain' || 'contact' || 'contactID' || 'host'; (pseudo-code))
     *
     * @param UpdateFieldInterface $field The field to remove.
     * @return void
     */
    protected function remove(UpdateFieldInterface $field)
    {
        $this->remove[] = $field;
    }
}
