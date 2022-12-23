<?php

namespace BenMajor\GetAddress\Model;

class Collection
{
    private array $items;
    private int $length;
    private int $pointer;

    public function __construct()
    {
        $this->items = [];
        $this->length = 0;
        $this->pointer = 0;
    }

    /**
     * Add a new item to the collection
     *
     * @param  $item
     * @return self
     */
    public function add($item): self
    {
        $this->items[] = $item;
        $this->length++;

        return $this;
    }

    /**
     * Check if the collection is empty
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return $this->length === 0;
    }

    /**
     * Get the current pointer
     *
     * @return integer
     */
    public function getKey(): int
    {
        return $this->pointer;
    }

    /**
     * Get the first item
     */
    public function first()
    {
        return $this->items[0];
    }

    /**
     * Get the current item
     */
    public function current()
    {
        return $this->items[$this->pointer];
    }

    /**
     * Get the nexy item
     */
    public function next()
    {
        if ($this->pointer === ($this->length - 1)) {
            return null;
        }

        $next = $this->items[$this->pointer + 1];

        $this->pointer++;

        return $next;
    }

    /**
     * Get the number of items in the collection
     *
     * @return integer
     */
    public function count(): int
    {
        return $this->length;
    }

    /**
     * Rewind the collection one item
     */
    public function rewind()
    {
        if ($this->pointer === 0) {
            return null;
        }

        $prev = $this->items[$this->pointer - 1];

        $this->pointer--;

        return $prev;
    }

    /**
     * Get the last item
     */
    public function last()
    {
        return $this->items[$this->length - 1];
    }

    /**
     * Return the element at a specific index
     *
     * @param integer $index
     */
    public function get(int $index)
    {
        if (array_key_exists($index, $this->items) === false) {
            return null;
        }

        return $this->items[$index];
    }

    /**
     * Extract a slice of the collection
     *
     * @param integer $offset
     * @param integer $length
     */
    public function slice(int $offset, int $length)
    {
        return array_slice($this->items, $offset, $length);
    }

    /**
     * Apply the specified callback to each item
     *
     * @param callable $callback
     */
    public function each(callable $callback): void
    {
        foreach ($this->items as $key => $item) {
            call_user_func_array($callback, [ $item, $key ]);
        }
    }

    /**
     * Convert the collection to a traditional array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }
}
