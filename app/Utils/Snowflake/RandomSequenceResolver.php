<?php

namespace App\Utils\Snowflake;

use App\Contracts\SequenceResolver;

class RandomSequenceResolver implements SequenceResolver
{
    /**
     * The last timestamp.
     *
     * @var null
     */
    protected $lastTimeStamp = -1;

    /**
     * The sequence.
     *
     * @var int
     */
    protected $sequence = 0;

    /**
     *  {@inheritdoc}
     */
    public function sequence(int $currentTime)
    {
        if ($this->lastTimeStamp === $currentTime) {
            ++$this->sequence;
            $this->lastTimeStamp = $currentTime;

            return $this->sequence;
        }

        $this->sequence = 0;
        $this->lastTimeStamp = $currentTime;

        return 0;
    }
}
